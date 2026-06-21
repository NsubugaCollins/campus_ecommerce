<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
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
     * In a real integration this would call the MTN / Airtel API to push a
     * USSD prompt to the subscriber's handset.  Here we record the pending
     * transaction and return the reference so the app can start polling.
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

        $reference = 'SUB-MM-' . strtoupper(Str::random(10));

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
     * Simulation logic:
     *   • For the first 8 seconds the payment stays "pending"  (USSD is on
     *     the user's phone).
     *   • After 8 seconds we auto-complete it (user "entered their PIN").
     *   • If it has already been completed, just return the success payload.
     *
     * In production, replace the timer logic with a real status query to the
     * MTN / Airtel API.
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

        // Simulate: auto-complete once 8 seconds have elapsed since creation.
        $elapsedSeconds = now()->diffInSeconds($payment->created_at);

        if ($elapsedSeconds < 8) {
            return response()->json([
                'status'  => 'pending',
                'message' => 'Waiting for PIN authorisation on your handset…',
            ]);
        }

        // ── Auto-complete the payment (USSD PIN accepted on handset) ────────
        $payment->update(['status' => 'completed']);

        $user   = $request->user();
        $expiry = $user->isSubscribed() ? $user->subscription_expires_at : now();

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
