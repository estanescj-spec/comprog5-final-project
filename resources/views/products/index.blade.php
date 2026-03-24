@extends('layouts.app')

@section('header')
    <div class="text-center mb-2">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight mb-2">Face and Body Skincare</h2>
    </div>
@endsection

@section('content')

    <div class="pt-6 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3 items-end" data-live-search-form>
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-xs font-medium text-gray-600 mb-1">Search product/service</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $filters['search'] ?? request('search') }}"
                            placeholder="I'm looking for..."
                            data-live-search
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-medium text-gray-600 mb-1">Category / Brand / Type</label>
                        @php
                            $selectedCategoryId = $filters['category']
                                ?? (isset($category) ? $category->id : request()->query('category'));
                        @endphp
                        <select
                            id="category"
                            name="category"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">All</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((string) $selectedCategoryId === (string) $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="min_price" class="block text-xs font-medium text-gray-600 mb-1">Min Price</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="min_price"
                            name="min_price"
                            value="{{ $filters['min_price'] ?? request('min_price') }}"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="max_price" class="block text-xs font-medium text-gray-600 mb-1">Max Price</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="max_price"
                            name="max_price"
                            value="{{ $filters['max_price'] ?? request('max_price') }}"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800">
                            Apply
                        </button>
                    </div>

                    <div>
                        <a href="{{ route('products.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-full hover:bg-gray-200">
                            Clear
                        </a>
                    </div>
                </form>

                @if (!empty($filters['search'] ?? request('search')) || !empty($filters['category'] ?? request('category')) || isset($filters['min_price']) || isset($filters['max_price']) || request()->filled('min_price') || request()->filled('max_price'))
                    <p class="mt-2 text-xs text-gray-500">Filtered results shown</p>
                @endif

                @error('max_price')
                    <p class="mt-2 text-sm text-blue-800">{{ $message }}</p>
                @enderror
            </div>

            @if ($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="relative bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow cursor-pointer"
                             data-href="{{ route('products.show', $product) }}"
                             role="link"
                             tabindex="0"
                             onclick="window.location.href=this.dataset.href"
                             onkeydown="if(event.key==='Enter' || event.key===' '){ event.preventDefault(); window.location.href=this.dataset.href; }">
                                @php
                                    $cardImage = ($product->images && $product->images->isNotEmpty())
                                        ? asset('storage/' . $product->images->first()->image_path)
                                        : ($product->variants->firstWhere('image', '!=', null)?->image
                                            ? asset('storage/' . $product->variants->firstWhere('image', '!=', null)->image)
                                            : null);
                                @endphp
                                @if ($cardImage)
                                    <div class="aspect-square overflow-hidden bg-gray-100">
                                        <img src="{{ $cardImage }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="aspect-square bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                    @auth
                                        @php $isFavorited = auth()->user()->favorites->where('product_id', $product->id)->count() > 0; @endphp
                                        <form method="POST" action="{{ $isFavorited ? route('favorites.destroy', $product) : route('favorites.store') }}" class="absolute top-2 right-2 z-10">
                                            @csrf
                                            @if($isFavorited)
                                                @method('DELETE')
                                                <button type="submit" class="text-pink-600 hover:text-pink-800" title="Remove from favorites">
                                                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                                </button>
                                            @else
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="text-gray-400 hover:text-pink-600" title="Add to favorites">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/></svg>
                                                </button>
                                            @endif
                                        </form>
                                    @endauth

                            <div class="p-4">
                                <div class="mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-900">
                                        {{ $product->categories->pluck('name')->join(', ') }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    {{ $product->name }}
                                </h3>

                                <div class="mb-2 flex items-center gap-2">
                                    @if (!is_null($product->ratings_avg_rating))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                            ★ {{ number_format((float) $product->ratings_avg_rating, 1) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            No ratings
                                        </span>
                                    @endif

                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ (int) ($product->units_sold ?? 0) }} sold
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    {{ $product->description }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-gray-500">From</span>
                                        <span class="text-xl font-bold text-gray-900">₱{{ number_format($product->variants->min('price') ?? 0, 2) }}</span>
                                    </div>
                                </div>

                                @if ($product->variants->count() > 0)
                                    <div class="mt-2 text-xs text-gray-500">
                                        {{ $product->variants->count() }} variant(s) available
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-600 mb-4">
                        @if (request('search'))
                            We couldn't find any products matching "{{ request('search') }}".
                        @elseif (isset($category))
                            No products in the {{ $category->name ?? 'Unknown' }} category yet.
                        @else
                            No products available at the moment.
                        @endif
                    </p>
                    @if (request('search'))
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800">
                            View All Products
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
