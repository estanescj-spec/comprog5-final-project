<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|integer|exists:categories,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
        ]);

        $search = $filters['search'] ?? null;

        if (!empty($search)) {
            $products = Product::search($search)
                ->query(function ($query) use ($filters) {
                    $query->with(['categories', 'variants', 'images']);
                    $this->applySalesAndRatingMetrics($query);
                    $this->applyCategoryAndPriceFilters($query, $filters);
                })
                ->paginate(12)
                ->withQueryString();
        } else {
            $query = Product::with(['categories', 'variants', 'images']);
            $this->applySalesAndRatingMetrics($query);

            $this->applyCategoryAndPriceFilters($query, $filters);

            $products = $query->latest()->paginate(12)->withQueryString();
        }

        $categories = Category::withCount('products')->orderBy('name')->get();
        $category = !empty($filters['category']) ? Category::find($filters['category']) : null;

        return view('products.index', compact('products', 'categories', 'category', 'filters'));
    }

    public function show(Product $product, Request $request)
    {
        $product->load(['categories', 'variants', 'images', 'ratings.user']);

        $averageRating = $product->ratings()->avg('rating');
        $ratingsCount = $product->ratings()->count();
        $existingUserRating = null;

        // Check if current user has purchased this product
        $userHasPurchased = false;
        if ($request->user()) {
            $existingUserRating = $product->ratings
                ->firstWhere('user_id', $request->user()->id);

            $userHasPurchased = OrderItem::query()
                ->whereHas('order', function ($q) use ($request) {
                    $q->where('user_id', $request->user()->id)
                      ->whereIn('status', ['completed', 'ongoing']);
                })
                ->whereHas('variant', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->exists();
        }

        $reviewVariantByUser = [];
        $reviewUserIds = $product->ratings->pluck('user_id')->unique()->values();

        if ($reviewUserIds->isNotEmpty()) {
            $reviewVariantByUser = OrderItem::query()
                ->select('orders.user_id', 'product_variants.variant_name')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')
                ->whereIn('orders.user_id', $reviewUserIds)
                ->whereIn('orders.status', ['completed', 'ongoing'])
                ->where('product_variants.product_id', $product->id)
                ->get()
                ->groupBy('user_id')
                ->map(function ($rows) {
                    return $rows
                        ->pluck('variant_name')
                        ->map(fn ($name) => $name ?: 'Default Variant')
                        ->unique()
                        ->values()
                        ->implode(', ');
                })
                ->toArray();
        }

        return view('products.show', compact('product', 'averageRating', 'ratingsCount', 'userHasPurchased', 'existingUserRating', 'reviewVariantByUser'));
    }

    public function categoriesIndex(Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|integer|exists:categories,id',
        ]);

        $search = $filters['search'] ?? null;

        if (!empty($search)) {
            $products = Product::search($search)
                ->query(function ($query) use ($filters) {
                    $query->with(['categories', 'variants', 'images']);

                    $this->applySalesAndRatingMetrics($query);

                    if (!empty($filters['category'])) {
                        $query->whereHas('categories', function ($q) use ($filters) {
                            $q->where('categories.id', $filters['category']);
                        });
                    }
                })
                ->paginate(12)
                ->withQueryString();
        } else {
            $query = Product::with(['categories', 'variants', 'images']);

            $this->applySalesAndRatingMetrics($query);

            if (!empty($filters['category'])) {
                $query->whereHas('categories', function ($q) use ($filters) {
                    $q->where('categories.id', $filters['category']);
                });
            }

            $products = $query->latest()->paginate(12)->withQueryString();
        }

        $categories = Category::withCount('products')->orderBy('name')->get();
        $selectedCategory = !empty($filters['category']) ? Category::find($filters['category']) : null;

        return view('categories.index', compact('products', 'categories', 'selectedCategory', 'filters'));
    }

    public function byCategory(Category $category, Request $request)
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
        ]);

        $search = $filters['search'] ?? null;

        if (!empty($search)) {
            $products = Product::search($search)
                ->query(function ($query) use ($category, $filters) {
                    $query->with(['categories', 'variants', 'images'])
                        ->whereHas('categories', function ($q) use ($category) {
                            $q->where('categories.id', $category->id);
                        });

                    $this->applySalesAndRatingMetrics($query);

                    $this->applyPriceFilter($query, $filters['min_price'] ?? null, $filters['max_price'] ?? null);
                })
                ->paginate(12)
                ->withQueryString();
        } else {
            $products = $category->products()
                ->with(['categories', 'variants', 'images']);

            $this->applySalesAndRatingMetrics($products);

            $this->applyPriceFilter($products, $filters['min_price'] ?? null, $filters['max_price'] ?? null);

            $products = $products->latest()->paginate(12)->withQueryString();
        }
        
        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'category', 'filters'));
    }

    private function applyCategoryAndPriceFilters($query, array $filters): void
    {
        if (!empty($filters['category'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category']);
            });
        }

        $this->applyPriceFilter($query, $filters['min_price'] ?? null, $filters['max_price'] ?? null);
    }

    private function applyPriceFilter($query, $minPrice, $maxPrice): void
    {
        if ($minPrice !== null || $maxPrice !== null) {
            $query->whereHas('variants', function ($q) use ($minPrice, $maxPrice) {
                if ($minPrice !== null) {
                    $q->where('price', '>=', $minPrice);
                }
                if ($maxPrice !== null) {
                    $q->where('price', '<=', $maxPrice);
                }
            });
        }
    }

    private function applySalesAndRatingMetrics($query): void
    {
        $query->withAvg('ratings', 'rating')
            ->addSelect([
                'units_sold' => DB::table('order_items')
                    ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->whereIn('orders.status', ['ongoing', 'completed'])
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0)'),
            ]);
    }
}
