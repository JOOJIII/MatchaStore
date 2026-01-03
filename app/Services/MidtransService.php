<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Setup Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Create Snap transaction
     */
    public function createSnapToken(Order $order, User $user)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number . '-' . time(),
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => '081234567890', // You can add phone field to users table
            ],
            'item_details' => $this->getItemDetails($order),
            'enabled_payments' => $this->getEnabledPayments(),
            'callbacks' => [
                'finish' => route('checkout.success', $order->id),
                'error' => route('checkout.error'),
                'pending' => route('checkout.pending', $order->id),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            throw new \Exception('Failed to create payment transaction');
        }
    }

    /**
     * Get item details for Midtrans
     */
    private function getItemDetails(Order $order)
    {
        $items = [];
        
        foreach ($order->items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name' => $item->product->name,
            ];
        }

        // Add shipping cost if any
        $items[] = [
            'id' => 'SHIPPING',
            'price' => 0, // Free shipping for now
            'quantity' => 1,
            'name' => 'Shipping Cost',
        ];

        return $items;
    }

    /**
     * Get enabled payment methods
     */
    private function getEnabledPayments()
    {
        return [
            'credit_card',
            'gopay',
            'shopeepay',
            'bank_transfer',
            'echannel',
            'bca_klikbca',
            'bca_klikpay',
            'bri_epay',
            'cimb_clicks',
            'danamon_online',
            'qris',
        ];
    }

    /**
     * Handle notification from Midtrans
     */
    public function handleNotification($notification)
    {
        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;

        // Extract order number from order_id (format: ORDER-NUMBER-timestamp)
        $orderNumber = explode('-', $orderId)[0];
        
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return ['status' => 'error', 'message' => 'Order not found'];
        }

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->update([
                        'payment_status' => 'challenge',
                        'status' => 'pending'
                    ]);
                } else {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing'
                    ]);
                }
            }
        } elseif ($transaction == 'settlement') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing'
            ]);
        } elseif ($transaction == 'pending') {
            $order->update([
                'payment_status' => 'pending',
                'status' => 'pending'
            ]);
        } elseif ($transaction == 'deny') {
            $order->update([
                'payment_status' => 'denied',
                'status' => 'cancelled'
            ]);
        } elseif ($transaction == 'expire') {
            $order->update([
                'payment_status' => 'expired',
                'status' => 'cancelled'
            ]);
        } elseif ($transaction == 'cancel') {
            $order->update([
                'payment_status' => 'cancelled',
                'status' => 'cancelled'
            ]);
        }

        // Save payment record
        $this->savePaymentRecord($order, $notification);

        return ['status' => 'success', 'order' => $order];
    }

    /**
     * Save payment record to database
     */
    private function savePaymentRecord(Order $order, $notification)
    {
        \App\Models\Payment::updateOrCreate(
            ['transaction_id' => $notification->transaction_id],
            [
                'order_id' => $order->id,
                'amount' => $notification->gross_amount,
                'payment_type' => $notification->payment_type,
                'status' => $notification->transaction_status,
                'response_data' => json_encode($notification),
            ]
        );
    }
}