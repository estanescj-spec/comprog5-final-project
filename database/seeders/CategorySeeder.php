<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Face Wash',
                'description' => 'Gentle cleansers for all skin types, including dry, oily, and acne-prone skin.',
            ],
            [
                'name' => 'Face Moisturizer',
                'description' => ' Dermatologist-developed moisturizers and creams tailored to every skin type.',
            ],
            [
                'name' => 'Face Serum',
                'description' => 'Targeted serums to reduce fine lines, dark spots, and brighten the skin.',
            ],
            [
                'name' => 'Face Toner',
                'description' => 'Toners and mists for all skin types to address various skincare concerns.',
            ],
            [
                'name' => 'Sunscreen',
                'description' => 'Dermatologist-recommended UVA/UVB protection for face and body.',
            ],
            [
                'name' => 'Acne Products',
                'description' => 'Effective skincare solutions for clearer, more confident, acne-free skin.',
            ],
            [
                'name' => 'Eye Cream',
                'description' => 'Formulas for the delicate eye area targeting dark circles, fine lines, and puffiness.',
            ],
            [
                'name' => 'Body Lotion',
                'description' => 'Our body moisturizers for dry skin and eczema are fragrance-free and suitable for sensitive skin. These unique formulas help soothe and reduce dry, rough skin.',
            ],
            [
                'name' => 'Body Wash',
                'description' => 'Discover our full range of body sunscreen all suitable for sensitive skin.',
            ],
            [
                'name' => 'Dry Skin',
                'description' => 'Richly hydrating formulas designed to nourish, soothe, and restore moisture to dry and flaky skin.',
            ],
            [
                'name' => 'Oily Skin',
                'description' => 'Products formulated to control excess sebum, minimize pores, and keep skin shine-free throughout the day.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
