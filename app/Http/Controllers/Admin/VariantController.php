<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variant_name' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('variants', 'public');
        }

        $product->variants()->create($validated);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Variant added successfully.');
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'variant_name' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $validated['image'] = $request->file('image')->store('variants', 'public');
        }

        $variant->update($validated);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Variant updated successfully.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->delete();

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Variant deleted successfully.');
    }
}
