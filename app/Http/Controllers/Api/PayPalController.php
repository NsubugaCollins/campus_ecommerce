<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    public function createPayment(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->payment_method !== 'paypal') {
            return response()->json(['message' => 'Order is not a PayPal order'], 422);
        }

        $amountInUSD = round($order->total_amount / 3750, 2);
        $baseUrl = rtrim(config('app.url'), '/');

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => $baseUrl.'/paypal/success/'.$order->id,
                'cancel_url' => $baseUrl.'/paypal/cancel/'.$order->id,
            ],
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => $amountInUSD,
                ],
            ]],
        ]);

        if (isset($response['id'])) {
            foreach ($response['links'] ?? [] as $link) {
                if ($link['rel'] === 'approve') {
                    $order->update(['paypal_order_id' => $response['id']]);

                    return response()->json([
                        'approval_url' => $link['href'],
                        'paypal_order_id' => $response['id'],
                    ]);
                }
            }
        }

        return response()->json([
            'message' => $response['message'] ?? 'PayPal order creation failed',
        ], 422);
    }
}
