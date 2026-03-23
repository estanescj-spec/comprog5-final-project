<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $variants = ProductVariant::where('stock', '>', 0)->get();

        if ($customers->isEmpty() || $variants->isEmpty()) {
            return;
        }

        $itemCountPool = [2, 2, 3, 4];

        // User-centric, relational assignment
        foreach ($customers as $customer) {
            // Most customers have a cart, some do not
            if (rand(1, 100) > 85) {
                continue;
            }

            DB::transaction(function () use ($customer, $variants, $itemCountPool) {
                $createdAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23));

                $cart = Cart::create([
                    'user_id' => $customer->id,
                ]);

                $cart->created_at = $createdAt;
                $cart->updated_at = $createdAt;
                $cart->save();

                // Always at least 2 products in the cart
                $itemCount = min($itemCountPool[array_rand($itemCountPool)], $variants->count());
                if ($itemCount < 2) {
                    $itemCount = min(2, $variants->count());
                }

                $selectedVariants = $variants->shuffle()->take($itemCount);
                $latestItemTimestamp = $createdAt;

                foreach ($selectedVariants as $variant) {
                    $maxQty = min(4, (int) $variant->stock);
                    if ((float) $variant->price >= 4000) {
                        $maxQty = min($maxQty, 2);
                    }
                    $quantity = rand(1, max(1, $maxQty));
                    $itemTime = (clone $createdAt)->addMinutes(rand(1, 180));

                    $item = CartItem::create([
                        'cart_id' => $cart->id,
                        'product_variant_id' => $variant->id,
                        'quantity' => $quantity,
                    ]);

                    $item->created_at = $itemTime;
                    $item->updated_at = $itemTime;
                    $item->save();

                    if ($itemTime->greaterThan($latestItemTimestamp)) {
                        $latestItemTimestamp = $itemTime;
                    }
                }

                $cart->updated_at = $latestItemTimestamp;
                $cart->save();
            });
        }
    }
}
