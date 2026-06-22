<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Services\MtnMomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Log;

class SubscriptionController extends Controller
{
    protected MtnMomoService $momoService;

    public function __construct(MtnMomoService $momoService)
    {
        $this->momoService = $momoService;
    }

    /**
     * Return the authenticated user's current subscription status.
     */
    public function getStatus(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'is_subscribed'          => $user->isSubscribed(),
            'subscription_type'      => $user->subscription_type,
            'subscription_expires_at'=> $user->subscription_expires_at
                                            ? $user->subscription_expires_at->toIso8601String()
                                            : null,
        ]);
    }

    /**
     * Initiate a USSD-push payment request.
     *
     * Calls the MTN MoMo collections service to trigger a USSD push.
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'provider'     => 'required|string|in:mtn,airtel',
            'plan'         => 'required|string|in:daily,weekly,monthly',
        ]);

        $amounts = ['daily' => 1000, 'weekly' => 5000, 'monthly' => 15000];
        $amount  = $amounts[$request->plan];

        $externalId = 'SUB-MM-' . strtoupper(Str::random(10));

        try {
            if ($request->provider === 'mtn') {
                // Request-to-pay triggers a real MTN MoMo USSD push on the subscriber's phone
                $reference = $this->momoService->requestToPay(
                    $externalId,
                    $request->phone_number,
                    $amount,
                    'Campus Mall ' . ucfirst($request->plan) . ' Subscription',
                    'Campus Mall payment for ' . $request->plan . ' plan'
                );
            } else {
                // Keep simulated reference for Airtel
                $reference = 'SIM-' . (string) Str::uuid();
            }
        } catch (\Exception $e) {
            Log::error('[Subscription] MoMo initiation error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json([
                'status'  => 'failed',
                'message' => 'Could not initiate payment with the network operator: ' . $e->getMessage(),
            ], 502);
        }

        SubscriptionPayment::create([
            'user_id'      => $request->user()->id,
            'phone_number' => $request->phone_number,
            'provider'     => $request->provider,
            'amount'       => $amount,
            'plan'         => $request->plan,
            'reference'    => $reference,
            'status'       => 'pending',
        ]);

        return response()->json([
            'status'    => 'initiated',
            'message'   => 'USSD push sent to ' . $request->phone_number . '. Please enter your PIN on your handset to authorise.',
            'reference' => $reference,
            'amount'    => $amount,
            'provider'  => $request->provider,
            'plan'      => $request->plan,
        ]);
    }

    /**
     * Poll endpoint – called by the app every few seconds.
     *
     * Check transaction status on MTN MoMo network or simulate for Airtel.
     */
    public function checkPaymentStatus(Request $request, string $reference)
    {
        $payment = SubscriptionPayment::where('reference', $reference)
                        ->where('user_id', $request->user()->id)
                        ->firstOrFail();

        // Already done – return final state immediately.
        if ($payment->status === 'completed') {
            return response()->json([
                'status' => 'completed',
                'user'   => $this->formatUser($request->user()->fresh()),
            ]);
        }

        if ($payment->status === 'failed') {
            return response()->json(['status' => 'failed',  'message' => 'Payment was declined.'], 402);
        }

        // MTN MoMo real network status polling
        if ($payment->provider === 'mtn') {
            try {
                $momoTx = $this->momoService->getTransactionStatus($reference);
                $momoStatus = strtoupper($momoTx['status'] ?? 'PENDING');

                if ($momoStatus === 'SUCCESSFUL') {
                    return $this->completePayment($payment, $request->user());
                }

                if ($momoStatus === 'FAILED') {
                    $payment->update(['status' => 'failed']);
                    return response()->json(['status' => 'failed', 'message' => 'Payment was declined by MTN.'], 402);
                }

                // Still PENDING
                return response()->json([
                    'status'  => 'pending',
                    'message' => 'Waiting for PIN authorisation on your handset…',
                ]);
            } catch (\Exception $e) {
                Log::error('[Subscription] MoMo status query error: ' . $e->getMessage());
                // Fallback: stay pending to survive transient connection errors during polling
                return response()->json([
                    'status'  => 'pending',
                    'message' => 'Waiting for PIN authorisation on your handset…',
                ]);
            }
        }

        // Airtel simulation logic (if not mtn)
        $elapsedSeconds = now()->diffInSeconds($payment->created_at);

        if ($elapsedSeconds < 8) {
            return response()->json([
                'status'  => 'pending',
                'message' => 'Waiting for PIN authorisation on your handset…',
            ]);
        }

        // ── Auto-complete simulated payment ────────
        return $this->completePayment($payment, $request->user());
    }

    /**
     * Helper to transition subscription payment to complete.
     */
    protected function completePayment(SubscriptionPayment $payment, $user)
    {
        $payment->update(['status' => 'completed']);

        $expiry = ($user->isSubscribed() && $user->subscription_expires_at)
            ? Carbon::parse($user->subscription_expires_at)
            : now();

        match ($payment->plan) {
            'daily'   => $expiry = $expiry->addDay(),
            'weekly'  => $expiry = $expiry->addWeek(),
            'monthly' => $expiry = $expiry->addDays(30),
        };

        $user->update([
            'subscription_type'       => 'premium_' . $payment->plan,
            'subscription_expires_at' => $expiry,
        ]);

        return response()->json([
            'status' => 'completed',
            'user'   => $this->formatUser($user->fresh()),
        ]);
    }

    /**
     * Allow a user to cancel a pending payment (e.g. they dismissed the
     * USSD prompt on their phone).
     */
    public function cancelPayment(Request $request, string $reference)
    {
        $payment = SubscriptionPayment::where('reference', $reference)
                        ->where('user_id', $request->user()->id)
                        ->where('status', 'pending')
                        ->firstOrFail();

        $payment->update(['status' => 'failed']);

        return response()->json(['status' => 'cancelled', 'message' => 'Payment cancelled.']);
    }

    protected function formatUser($user): array
    {
        return [
            'id'                      => $user->id,
            'name'                    => $user->name,
            'email'                   => $user->email,
            'phone'                   => $user->phone,
            'role'                    => $user->role ?? 'user',
            'points'                  => (int) $user->points,
            'referral_code'           => $user->referral_code,
            'login_count'             => (int) $user->login_count,
            'subscription_type'       => $user->subscription_type,
            'subscription_expires_at' => $user->subscription_expires_at
                                            ? $user->subscription_expires_at->toIso8601String()
                                            : null,
        ];
    }
}

