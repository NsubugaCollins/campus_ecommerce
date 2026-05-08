<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Order;

class PayPalController extends Controller
{
    public function payment(Order $order)
    {
        // Conversion rate: 1 USD = 3750 UGX
        $amountInUSD = round($order->total_amount / 3750, 2);


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success', $order->id),
                "cancel_url" => route('paypal.cancel', $order->id),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amountInUSD
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    $order->update([
                        'paypal_order_id' => $response['id']
                    ]);
                    return redirect()->away($link['href']);
                }
            }
        }

        \Illuminate\Support\Facades\Log::error('PayPal Order Creation Failed', ['response' => $response]);
        return redirect()->route('checkout.index')->with('error', 'Something went wrong with PayPal payment: ' . ($response['message'] ?? 'Unknown error'));

    }

    public function success(Request $request, Order $order)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing'
            ]);



            // Clear the cart
            session()->forget('cart');

            return redirect()->route('orders.index')->with('success', 'Payment successful and order placed!');
        }


        return redirect()->route('checkout.index')->with('error', 'Payment failed or was not completed.');
    }

    public function cancel(Order $order)
    {
        return redirect()->route('checkout.index')->with('error', 'Payment was cancelled.');
    }
}
