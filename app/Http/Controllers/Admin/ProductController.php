<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'variants', 'images'])
            ->withCount('variants')
            ->withAvg('ratings', 'rating')
            ->addSelect([
                'units_sold' => DB::table('order_items')
                    ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->whereIn('orders.status', ['ongoing', 'completed'])
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0)'),
            ])
            ->latest()
            ->get();

        $trashedProducts = Product::onlyTrashed()
            ->with(['categories', 'variants', 'images'])
            ->withCount('variants')
            ->withAvg('ratings', 'rating')
            ->addSelect([
                'units_sold' => DB::table('order_items')
                    ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->whereIn('orders.status', ['ongoing', 'completed'])
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0)'),
            ])
            ->latest('deleted_at')
            ->get();
        
        return view('admin.products.index', compact('products', 'trashedProducts'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.variant_name' => 'required|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Attach categories (many-to-many)
        $product->categories()->sync($request->input('categories', []));

        // Handle main product image (stored in product_images as primary)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->images()->create([
                'image_path' => $path,
                'order' => 0,
                'is_primary' => true,
            ]);
        }

        // Handle product gallery images
        if ($request->hasFile('images')) {
            $maxOrder = $product->images()->max('order') ?? -1;
            foreach ($request->file('images') as $index => $img) {
                $path = $img->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'order' => $maxOrder + $index + 1,
                    'is_primary' => !$product->images()->where('is_primary', true)->exists() && $index === 0,
                ]);
            }
        }

        // Create variants
        foreach ($request->variants as $variantData) {
            $product->variants()->create([
                'variant_name' => $variantData['variant_name'],
                'price' => $variantData['price'],
                'stock' => $variantData['stock'],
                'image' => isset($variantData['image']) ? $variantData['image']->store('variants', 'public') : null,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load(['variants', 'categories', 'images']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.variant_name' => 'nullable|string|max:50',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete selected images
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Main image update (stored in product_images as primary)
        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('products', 'public');
            $primaryImage = $product->images()->where('is_primary', true)->first();

            if ($primaryImage) {
                Storage::disk('public')->delete($primaryImage->image_path);
                $primaryImage->update(['image_path' => $newPath]);
            } else {
                $minOrder = $product->images()->min('order');
                $product->images()->create([
                    'image_path' => $newPath,
                    'order' => $minOrder !== null ? ((int) $minOrder - 1) : 0,
                    'is_primary' => true,
                ]);
            }
        }

        // Add new additional images
        if ($request->hasFile('images')) {
            $maxOrder = $product->images()->max('order') ?? -1;
            foreach ($request->file('images') as $index => $img) {
                $product->images()->create([
                    'image_path' => $img->store('products', 'public'),
                    'order' => $maxOrder + $index + 1,
                    'is_primary' => false,
                ]);
            }
        }

        // Update product
        $product->update($validated);

        // Sync categories (many-to-many)
        $product->categories()->sync($request->input('categories', []));

        // Ensure one primary image exists when gallery is not empty.
        if (! $product->images()->where('is_primary', true)->exists()) {
            $fallbackImage = $product->images()->orderBy('order')->first();
            if ($fallbackImage) {
                $fallbackImage->update(['is_primary' => true]);
            }
        }

        // Handle variants
        if ($request->filled('variants')) {
            foreach ($request->variants as $variantData) {
                $variantPrice = $variantData['price'] ?? 0;
                $variantStock = $variantData['stock'] ?? 0;

                // Existing variant
                if (!empty($variantData['id'])) {
                    $variant = $product->variants()->find($variantData['id']);
                    if ($variant) {
                        $updateData = [
                            'variant_name' => $variantData['variant_name'] ?? $variant->variant_name,
                            'price' => $variantPrice,
                            'stock' => $variantStock,
                        ];
                        if (isset($variantData['image'])) {
                            if ($variant->image) {
                                Storage::disk('public')->delete($variant->image);
                            }
                            $updateData['image'] = $variantData['image']->store('variants', 'public');
                        }
                        $variant->update($updateData);
                    }
                }
                // New variant
                else if (!empty($variantData['variant_name']) || isset($variantData['price'])) {
                    $product->variants()->create([
                        'variant_name' => $variantData['variant_name'] ?? 'Variant',
                        'price' => $variantPrice,
                        'stock' => $variantStock,
                        'image' => isset($variantData['image']) ? $variantData['image']->store('variants', 'public') : null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product moved to trash successfully.');
    }

    public function restore(Product $product)
    {
        if (! $product->trashed()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Product is not in trash.');
        }

        $product->restore();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product restored successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new ProductsImport();
            Excel::import($import, $request->file('import_file'));

            return redirect()->route('admin.products.index')
                ->with('success', "Imported {$import->importedCount} product(s) successfully.");
        } catch (\Throwable $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Import failed. Please check the worksheet format and try again.');
        }
    }
}
