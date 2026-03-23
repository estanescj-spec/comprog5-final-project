<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $variants = ProductVariant::with('product')->get();

        if ($customers->isEmpty() || $variants->isEmpty()) {
            return;
        }

        // Weighted status pool to keep data distribution realistic.
        $statusPool = [
            'completed', 'completed', 'completed',
            'ongoing', 'ongoing',
            'pending',
            'canceled',
        ];

        $fallbackAddresses = [
            '123 Sample Street, Manila',
            '45 Aurora Blvd, Quezon City',
            '78 Shaw Boulevard, Mandaluyong City',
            '9 Ayala Avenue, Makati City',
            '22 Roxas Blvd, Pasay City',
        ];

        $paymentMethods = ['COD', 'GCash', 'card', 'Maya', 'BankTransfer'];

        $allStatuses = ['pending', 'ongoing', 'completed', 'canceled'];
        foreach ($customers as $customer) {
            // Ensure at least one order per status for each customer
            foreach ($allStatuses as $status) {
                DB::transaction(function () use ($customer, $variants, $status, $fallbackAddresses, $paymentMethods) {
                    $shippingAddress = $customer->address ?: $fallbackAddresses[array_rand($fallbackAddresses)];
                    $orderedAt = Carbon::now()->subDays(rand(1, 120));

                    $order = Order::create([
                        'user_id'          => $customer->id,
                        'status'           => $status,
                        'shipping_address' => $shippingAddress,
                    ]);

                    $order->created_at = $orderedAt;
                    $order->updated_at = $orderedAt;
                    $order->save();

                    // 1-4 distinct items per order.
                    $itemCount = min(rand(1, 4), $variants->count());
                    $selectedVariants = $variants->shuffle()->take($itemCount);

                    $totalAmount = 0;

                    foreach ($selectedVariants as $variant) {
                        $quantity = rand(1, 3);
                        $discountPercent = collect([0, 0, 0, 5, 10])->random();
                        $snapshotPrice = round((float) $variant->price * (1 - ($discountPercent / 100)), 2);
                        $snapshotPrice = max($snapshotPrice, 1);

                        $item = OrderItem::create([
                            'order_id'           => $order->id,
                            'product_variant_id' => $variant->id,
                            'quantity'           => $quantity,
                            'unit_price'         => $snapshotPrice,
                        ]);

                        $item->created_at = (clone $orderedAt)->addMinutes(rand(2, 20));
                        $item->updated_at = $item->created_at;
                        $item->save();

                        $totalAmount += $snapshotPrice * $quantity;
                    }

                    $method = $paymentMethods[array_rand($paymentMethods)];
                    $paymentStatus = 'pending';
                    $paidAt = null;

                    if ($status === 'completed') {
                        $paymentStatus = 'paid';
                        $paidAt = (clone $orderedAt)->addHours(rand(1, 72));
                    } elseif ($status === 'ongoing') {
                        if ($method === 'COD') {
                            $paymentStatus = 'pending';
                        } else {
                            $paymentStatus = collect(['paid', 'pending'])->random();
                            $paidAt = $paymentStatus === 'paid'
                                ? (clone $orderedAt)->addHours(rand(1, 48))
                                : null;
                        }
                    } elseif ($status === 'canceled') {
                        $paymentStatus = $method === 'COD' ? 'pending' : 'failed';
                    }

                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'amount'   => round($totalAmount, 2),
                        'method'   => $method,
                        'status'   => $paymentStatus,
                        'paid_at'  => $paidAt,
                    ]);

                    $payment->created_at = (clone $orderedAt)->addMinutes(rand(5, 45));
                    $payment->updated_at = $payment->created_at;
                    $payment->save();

                    if ($status === 'completed') {
                        $order->updated_at = $paidAt ?: (clone $orderedAt)->addDays(rand(1, 7));
                    } elseif ($status === 'ongoing') {
                        $order->updated_at = (clone $orderedAt)->addDays(rand(1, 5));
                    } elseif ($status === 'canceled') {
                        $order->updated_at = (clone $orderedAt)->addDays(rand(0, 3));
                    } else {
                        $order->updated_at = (clone $orderedAt)->addHours(rand(2, 24));
                    }
                    $order->save();

                    if ($status === 'completed') {
                        $customerModel = User::find($customer->id);
                        if ($customerModel && !$customerModel->hasVerifiedEmail()) {
                            $customerModel->markEmailAsVerified();
                        }
                    }
                });
            }
            // Optionally add a few more random orders for realism
            $extraOrders = rand(0, 2);
            for ($i = 0; $i < $extraOrders; $i++) {
                DB::transaction(function () use ($customer, $variants, $statusPool, $fallbackAddresses, $paymentMethods) {
                    $status = $statusPool[array_rand($statusPool)];
                    $shippingAddress = $customer->address ?: $fallbackAddresses[array_rand($fallbackAddresses)];
                    $orderedAt = Carbon::now()->subDays(rand(1, 120));
                    $order = Order::create([
                        'user_id'          => $customer->id,
                        'status'           => $status,
                        'shipping_address' => $shippingAddress,
                    ]);
                    $order->created_at = $orderedAt;
                    $order->updated_at = $orderedAt;
                    $order->save();
                    $itemCount = min(rand(1, 4), $variants->count());
                    $selectedVariants = $variants->shuffle()->take($itemCount);
                    $totalAmount = 0;
                    foreach ($selectedVariants as $variant) {
                        $quantity = rand(1, 3);
                        $discountPercent = collect([0, 0, 0, 5, 10])->random();
                        $snapshotPrice = round((float) $variant->price * (1 - ($discountPercent / 100)), 2);
                        $snapshotPrice = max($snapshotPrice, 1);
                        $item = OrderItem::create([
                            'order_id'           => $order->id,
                            'product_variant_id' => $variant->id,
                            'quantity'           => $quantity,
                            'unit_price'         => $snapshotPrice,
                        ]);
                        $item->created_at = (clone $orderedAt)->addMinutes(rand(2, 20));
                        $item->updated_at = $item->created_at;
                        $item->save();
                        $totalAmount += $snapshotPrice * $quantity;
                    }
                    $method = $paymentMethods[array_rand($paymentMethods)];
                    $paymentStatus = 'pending';
                    $paidAt = null;
                    if ($status === 'completed') {
                        $paymentStatus = 'paid';
                        $paidAt = (clone $orderedAt)->addHours(rand(1, 72));
                    } elseif ($status === 'ongoing') {
                        if ($method === 'COD') {
                            $paymentStatus = 'pending';
                        } else {
                            $paymentStatus = collect(['paid', 'pending'])->random();
                            $paidAt = $paymentStatus === 'paid'
                                ? (clone $orderedAt)->addHours(rand(1, 48))
                                : null;
                        }
                    } elseif ($status === 'canceled') {
                        $paymentStatus = $method === 'COD' ? 'pending' : 'failed';
                    }
                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'amount'   => round($totalAmount, 2),
                        'method'   => $method,
                        'status'   => $paymentStatus,
                        'paid_at'  => $paidAt,
                    ]);
                    $payment->created_at = (clone $orderedAt)->addMinutes(rand(5, 45));
                    $payment->updated_at = $payment->created_at;
                    $payment->save();
                    if ($status === 'completed') {
                        $order->updated_at = $paidAt ?: (clone $orderedAt)->addDays(rand(1, 7));
                    } elseif ($status === 'ongoing') {
                        $order->updated_at = (clone $orderedAt)->addDays(rand(1, 5));
                    } elseif ($status === 'canceled') {
                        $order->updated_at = (clone $orderedAt)->addDays(rand(0, 3));
                    } else {
                        $order->updated_at = (clone $orderedAt)->addHours(rand(2, 24));
                    }
                    $order->save();
                    if ($status === 'completed') {
                        $customerModel = User::find($customer->id);
                        if ($customerModel && !$customerModel->hasVerifiedEmail()) {
                            $customerModel->markEmailAsVerified();
                        }
                    }
                });
            }
        }
    }
}
