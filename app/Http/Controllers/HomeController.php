<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $featuredImagePath = SiteSetting::where('key', 'home_featured_image')->value('value');
        $bannerTitle = SiteSetting::where('key', 'home_banner_title')->value('value') ?: 'Glow Starts Here';
        $bannerSubtitle = SiteSetting::where('key', 'home_banner_subtitle')->value('value')
            ?: 'Discover dermatologist-loved skincare essentials and find your perfect routine with FLEUR DE PEAU.';


        $newReleases = Product::with(['images', 'variants'])
            ->withAvg('ratings', 'rating')
            ->addSelect([
                'units_sold' => DB::table('order_items')
                    ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->where('orders.status', 'completed')
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0)'),
            ])
            ->latest()
            ->take(6)
            ->get();

        $bestSellers = Product::with(['images', 'variants'])
            ->withAvg('ratings', 'rating')
            ->addSelect([
                'units_sold' => DB::table('order_items')
                    ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->where('orders.status', 'completed')
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0)'),
            ])
            ->orderByDesc('units_sold')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('featuredImagePath', 'bannerTitle', 'bannerSubtitle', 'newReleases', 'bestSellers'));
    }

    public function updateFeaturedImage(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $oldPath = SiteSetting::where('key', 'home_featured_image')->value('value');

        $newPath = $validated['featured_image']->store('home', 'public');

        SiteSetting::updateOrCreate(
            ['key' => 'home_featured_image'],
            ['value' => $newPath]
        );

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return back()->with('success', 'Home featured photo updated successfully.');
    }
}
