@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Category') }}
    </h2>
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        @php
                            $selectedProducts = old('product_ids', $category->products->pluck('id')->map(fn($id) => (int) $id)->all());
                        @endphp

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="border-radius: 0.375rem !important;">
                            @error('name')
                                <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="border-radius: 0.375rem !important;">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">Products in this Category</label>
                                <span class="text-xs text-gray-500">Select to add, uncheck to remove</span>
                            </div>

                            <input type="text" id="product-filter" placeholder="Search products..."
                                class="mb-3 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" style="border-radius: 0.375rem !important;">

                            <div id="product-list" class="max-h-72 overflow-y-auto border border-gray-200 rounded-lg p-3 grid grid-cols-1 md:grid-cols-2 gap-2">
                                @forelse ($products as $product)
                                    <label class="product-option flex items-center gap-2 p-2 border rounded-lg cursor-pointer hover:bg-blue-50" data-name="{{ strtolower($product->name) }}" style="border-radius: 0.375rem !important;">
                                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                                            @checked(in_array((int) $product->id, $selectedProducts, true))
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" style="border-radius: 0.375rem !important;">
                                        <span class="text-sm text-gray-700">{{ $product->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No products available.</p>
                                @endforelse
                            </div>

                            @error('product_ids')
                                <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                            @enderror
                            @error('product_ids.*')
                                <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                                Update Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-full hover:bg-gray-300" style="border-radius: 0.375rem !important;">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterInput = document.getElementById('product-filter');
            const options = document.querySelectorAll('.product-option');

            if (!filterInput) return;

            filterInput.addEventListener('input', function () {
                const keyword = this.value.trim().toLowerCase();

                options.forEach(function (option) {
                    const name = option.dataset.name || '';
                    option.classList.toggle('hidden', keyword !== '' && !name.includes(keyword));
                });
            });
        });
    </script>
@endsection
