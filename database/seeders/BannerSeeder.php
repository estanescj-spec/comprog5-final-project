<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultBannerPath = Storage::disk('public')->exists('home/banner.jpg')
            ? 'home/banner.jpg'
            : null;

        SiteSetting::updateOrCreate(
            ['key' => 'home_featured_image'],
            ['value' => $defaultBannerPath]
        );

        SiteSetting::updateOrCreate(
            ['key' => 'home_banner_title'],
            ['value' => 'GLOW STARTS HERE']
        );

        SiteSetting::updateOrCreate(
            ['key' => 'home_banner_subtitle'],
            ['value' => 'Discover dermatologist-loved skincare essentials and find your perfect routine with FLEUR DE PEAU.']
        );
    }
}
