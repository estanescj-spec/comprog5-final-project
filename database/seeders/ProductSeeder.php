<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'categories'  => ['Face Wash', 'Dry Skin'],
                'name'        => 'Hydrating Cleanser',
                'description' => 'Clinically Tested, Dermatologist-Recommended Cleanser for Normal to Dry Skin',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '50ML',  'price' => 849.00, 'stock' => 40, 'image' => 'variants/dryCleanser50.jpg'],
                    ['variant_name' => '200ML', 'price' => 1499.00, 'stock' => 30, 'image' => 'variants/dryCleanser200.jpg'],
                    ['variant_name' => '400ML REFILL', 'price' => 1799.00, 'stock' => 20, 'image' => 'variants/dryCleanser400refill.jpg'],
                    ['variant_name' => '400ML', 'price' => 1999.00, 'stock' => 20, 'image' => 'variants/dryCleanser400.jpg'],
                ],
            ],
            [
                'categories'  => ['Face Wash', 'Oily Skin'],
                'name'        => 'Foaming Cleanser',
                'description' => 'Clinically Tested, Dermatologist-Recommended Cleanser for Normal to Oily Skin',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '50ML',  'price' => 899.00, 'stock' => 40, 'image' => 'variants/oilyCleanser50.jpg'],
                    ['variant_name' => '200ML', 'price' => 1599.00, 'stock' => 30, 'image' => 'variants/oilyCleanser200.jpg'],
                    ['variant_name' => '400ML REFILL', 'price' => 1799.00, 'stock' => 20, 'image' => 'variants/oilyCleanser400refill.jpg'],
                    ['variant_name' => '400ML', 'price' => 1999.00, 'stock' => 20, 'image' => 'variants/oilyCleanser400.jpg'],
                ],
            ],
            [
                'categories'  => ['Face Wash', 'Acne Products'],
                'name'        => 'Acne Face Wash',
                'description' => 'Clinically Tested, Dermatologist-Recommended Salicylic Acid Face Wash for Acne',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '50ML',  'price' => 1149.00, 'stock' => 40, 'image' => 'variants/acneWash50.jpg'],
                    ['variant_name' => '100ML', 'price' => 1399.00, 'stock' => 30, 'image' => 'variants/acneWash100.jpg'],
                    ['variant_name' => '200ML', 'price' => 1899.00, 'stock' => 20, 'image' => 'variants/acneWash200.jpg'],
                ],
            ],
            [
                'categories'  => ['Face Moisturizer', 'Sunscreen'],
                'name'        => 'Face Moisturizer With SPF',
                'description' => 'Face Moisturizer with SPF',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '40ML',  'price' => 1249.00, 'stock' => 40, 'image' => 'variants/moisturizerSunscreen40.jpg'],
                    ['variant_name' => '100ML', 'price' => 2599.00, 'stock' => 30, 'image' => 'variants/moisturizerSSunscreen100.jpg'],
                ],
            ],
            [
                'categories'  => ['Face Moisturizer', 'Oily Skin', 'Acne Products'],
                'name'        => 'Mattifying Face Moisturizer',
                'description' => 'Oil-Free Mattifying Face Moisturizer for Oily Skin',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '40ML (Default)',  'price' => 3999.00, 'stock' => 40, 'image' => 'variants/oilyMoisturizer.jpg'],
                ],
            ],
                        [
                'categories'  => ['Face Moisturizer', 'Dry Skin'],
                'name'        => 'Repairing Face Moisturizer',
                'description' => 'Face Moisturizer',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '40ML',  'price' => 1149.00, 'stock' => 40, 'image' => 'variants/dryMoisturizer40.jpg'],
                    ['variant_name' => '100ML', 'price' => 2499.00, 'stock' => 30, 'image' => 'variants/dryMoisturizer100.jpg'],
                ],
            ],
            [
                'categories'  => ['Face Serum'],
                'name'        => 'Hyaluronic Acid Serum',
                'description' => 'Dermatological Anti-Aging Pure Hyaluronic Acid Serum. Allergy Tested Formula',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '30ML',  'price' => 3999.00, 'stock' => 40, 'image' => 'variants/drySerum30.jpg'],
                    ['variant_name' => '50ML', 'price' => 5399.00, 'stock' => 30, 'image' => 'variants/drySerum50.jpg'],
                ],
            ],
            [
                'categories'  => ['Face Serum'],
                'name'        => 'Dark Spot Serum',
                'description' => 'Anti-Aging Melasyl™ and 10% Niacinamide Serum. Allergy Tested Formula',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '30ML',  'price' => 4499.00, 'stock' => 40, 'image' => 'variants/darkspotSerum30.jpg'],
                    ['variant_name' => '50ML', 'price' => 6099.00, 'stock' => 30, 'image' => 'variants/darkspotSerum50.jpg'],
                ],
            ],
            [
                'categories'  => ['Face Toner', 'Acne Products'],
                'name'        => 'Acne Toner',
                'description' => 'Acne Toner with Salicylic Acid',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => '200ML (Default)',  'price' => 1899.00, 'stock' => 40, 'image' => 'variants/acneToner.jpg'],
                ],
            ],
            [
                'categories'  => ['Acne Products'],
                'name'        => 'Adapalene Gel',
                'description' => 'Prescription-Strength Topical Retinoid for Acne',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => 'Default (45G)',  'price' => 3999.00, 'stock' => 40, 'image' => 'variants/adapaleneGel.jpg'],
                ],
            ],
            [
                'categories'  => ['Eye Cream'],
                'name'        => 'Retinol Eye Cream',
                'description' => 'Anti-Aging Retinol Eye Cream. Suitable for Sensitive Eyes. Ophthalmologist Tested.',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => 'Default (15ML)',  'price' => 4999.00, 'stock' => 40, 'image' => 'variants/retinolEyecream.jpg'],
                ],
            ],
            [
                'categories'  => ['Eye Cream'],
                'name'        => 'Vitamin C Eye Cream',
                'description' => 'Anti-Aging Vitamin C Eye Cream. Suitable for Sensitive Eyes. Ophthalmologist Tested.',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => 'Default (15ML)',  'price' => 4999.00, 'stock' => 40, 'image' => 'variants/vitamincEyecream.jpg'],
                ],
            ],
            [
                'categories'  => ['Sunscreen'],
                'name'        => 'Mineral Tinted Sunscreen',
                'description' => 'Tinted Mineral Sunscreen for Face',
                'image'       => null,
                'variants'    => [
                    ['variant_name' => 'Default (50ML)',  'price' => 3999.00, 'stock' => 40, 'image' => 'variants/mineralSunscreen.jpg'],
                ],
            ],
        ];

        foreach ($products as $item) {
            $categoryIds = Category::whereIn('name', (array) $item['categories'])->pluck('id');

            if ($categoryIds->isEmpty()) {
                continue;
            }

            $product = Product::create([
                'name'        => $item['name'],
                'description' => $item['description'],
            ]);

            $product->categories()->attach($categoryIds);

            foreach ($item['variants'] as $variant) {
                $product->variants()->create([
                    'variant_name' => $variant['variant_name'],
                    'price'        => $variant['price'],
                    'stock'        => $variant['stock'],
                    'image'        => $variant['image'],
                ]);
            }

            $primaryImage = $item['variants'][0]['image'] ?? null;
            if (!empty($primaryImage)) {
                $product->images()->create([
                    'image_path' => $primaryImage,
                    'order' => 0,
                    'is_primary' => true,
                ]);
            }
        }
    }
}