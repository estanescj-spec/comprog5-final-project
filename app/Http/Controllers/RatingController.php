<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RatingController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        // Check if user has purchased this product
        $hasPurchased = OrderItem::query()
            ->whereHas('order', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id)
                  ->whereIn('status', ['completed', 'ongoing']);
            })
            ->whereHas('variant', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->withErrors([
                'rating' => 'You can only rate products you have purchased.',
            ]);
        }

        $existingRating = Rating::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        $photoPath = $existingRating?->photo_path;

        if ($request->hasFile('photo')) {
            $newPhotoPath = $request->file('photo')->store('reviews', 'public');

            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            $photoPath = $newPhotoPath;
        }

        Rating::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'photo_path' => $photoPath,
            ]
        );

        return back()->with('success', 'Thank you for your feedback!');
    }
}

