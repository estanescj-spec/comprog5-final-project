<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $allProducts = \App\Models\Product::all();

        if ($customers->isEmpty() || $allProducts->isEmpty()) {
            return;
        }

        $positiveComments = [
            'Great product! Highly recommended.',
            'Exceeded my expectations. Very happy with this purchase.',
            'Good quality and fast delivery.',
            'Worth the price. Will buy again!',
            'Amazing product. Best skincare purchase ever.',
            'Perfect for my skin type.',
            'Love it! Already reordered.',
            'Effective and affordable.',
            'Excellent customer service as well.',
            'Highly recommended to friends and family.',
        ];
        $negativeComments = [
            'This product is shit.',
            'Gago, it broke me out.',
            'Putangina, waste of money.',
            'Tanga, don\'t buy this.',
            'Customer service was bitchy.',
            'Not worth the price, fuck this.',
            'Poor quality, what a bitch.',
            'Did not work for me, shit.',
            'Packaging was damaged, gago.',
            'Tanginamo, will not buy again.',
        ];

        // User-centric, relational assignment: each user reviews a subset of products
        $productIds = $allProducts->pluck('id')->all();
        $userCount = $customers->count();
        $productCount = $allProducts->count();

        // Each user reviews 1/3 of products, and every product gets at least one review
        $minReviewsPerProduct = 1;
        $reviewsPerUser = max(1, (int) ceil($productCount / 3));

        // Track which products have reviews
        $productReviewCounts = array_fill_keys($productIds, 0);

        $reviewTypes = ['no_comment', 'positive', 'negative'];
        $reviewTypeIdx = 0;

        foreach ($customers as $customer) {
            $reviewed = collect($productIds)->shuffle()->take($reviewsPerUser);
            foreach ($reviewed as $productId) {
                $type = $reviewTypes[$reviewTypeIdx % 3];
                $reviewTypeIdx++;
                if ($type === 'no_comment') {
                    $rating = rand(3, 5);
                    $comment = null;
                } elseif ($type === 'positive') {
                    $rating = rand(4, 5);
                    $comment = $positiveComments[array_rand($positiveComments)];
                } else {
                    $rating = rand(1, 2);
                    $comment = $negativeComments[array_rand($negativeComments)];
                }
                Rating::updateOrCreate([
                    'user_id'    => $customer->id,
                    'product_id' => $productId,
                ], [
                    'rating'  => $rating,
                    'comment' => $comment,
                ]);
                $productReviewCounts[$productId]++;
            }
        }

        // Ensure every product has at least one review
        foreach ($productReviewCounts as $productId => $count) {
            if ($count < $minReviewsPerProduct) {
                // Assign a random user to review this product
                $customer = $customers->random();
                $type = $reviewTypes[$reviewTypeIdx % 3];
                $reviewTypeIdx++;
                if ($type === 'no_comment') {
                    $rating = rand(3, 5);
                    $comment = null;
                } elseif ($type === 'positive') {
                    $rating = rand(4, 5);
                    $comment = $positiveComments[array_rand($positiveComments)];
                } else {
                    $rating = rand(1, 2);
                    $comment = $negativeComments[array_rand($negativeComments)];
                }
                Rating::updateOrCreate([
                    'user_id'    => $customer->id,
                    'product_id' => $productId,
                ], [
                    'rating'  => $rating,
                    'comment' => $comment,
                ]);
            }
        }
    }
}
