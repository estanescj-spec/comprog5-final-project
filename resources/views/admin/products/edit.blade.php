@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Product') }}
    </h2>
@endsection

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">Product Details</h3>

                <form id="product-edit-form" method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="border-radius: 0.375rem !important;">
                        @error('name')
                            <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                            @php
                                $selectedCategories = old('categories', $product->categories->pluck('id')->toArray());
                            @endphp
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-2 p-2 border rounded-lg cursor-pointer hover:bg-blue-50" style="border-radius: 0.375rem !important;">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                        {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" style="border-radius: 0.375rem !important;">
                                    <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('categories')
                            <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="border-radius: 0.375rem !important;">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Main Image (Single) -->
                    <div>
                        @php
                            $mainImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                        @endphp
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Main Product Image (Single)</label>
                        @if ($mainImage)
                            <div class="mb-2 flex items-start gap-3">
                                <div class="relative" id="image-card-{{ $mainImage->id }}">
                                    <img src="{{ asset('storage/' . $mainImage->image_path) }}" alt="{{ $product->name }}" class="h-24 w-24 object-cover rounded-lg border border-gray-200">
                                    <input id="delete-image-{{ $mainImage->id }}" type="checkbox" name="delete_images[]" value="{{ $mainImage->id }}" class="hidden">
                                    <button type="button" onclick="toggleImageDelete('{{ $mainImage->id }}')" class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-600 text-white text-sm leading-6 text-center hover:bg-red-700" style="border-radius: 50% !important;">×</button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Click × to mark for deletion. Changes apply when you click Update Product.</p>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewMainImage(event)"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100" style="border-radius: 0.375rem !important;">
                        <p class="mt-1 text-xs text-gray-500">Optional. Upload a new image to replace the current main image.</p>
                        <img id="main-image-preview" src="" alt="Main image preview" class="hidden mt-3 h-24 w-24 object-cover rounded-lg border border-gray-200">
                        @error('image')
                            <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Additional Images -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Images</label>
                        @php
                            $galleryImages = $product->images->reject(fn($img) => $mainImage && $img->id === $mainImage->id);
                        @endphp
                        @if ($galleryImages->count() > 0)
                            <div class="grid grid-cols-4 gap-3 mb-3">
                                @foreach ($galleryImages as $img)
                                    <div class="relative group" id="image-card-{{ $img->id }}">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $product->name }}" class="h-24 w-24 object-cover rounded-lg">
                                        <input id="delete-image-{{ $img->id }}" type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="hidden">
                                        <button type="button" onclick="toggleImageDelete('{{ $img->id }}')" class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-600 text-white text-sm leading-6 text-center hover:bg-red-700" style="border-radius: 50% !important;">×</button>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mb-2">Click × on an image to mark it for deletion. Changes apply when you click Update Product.</p>
                        @endif
                        <input type="file" name="images[]" id="images" accept="image/*" multiple onchange="previewImages(event)"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100" style="border-radius: 0.375rem !important;">
                        <p class="mt-1 text-xs text-gray-500">You can select multiple images to add. PNG, JPG, GIF up to 2MB each</p>
                        <div id="image-preview" class="mt-3 grid grid-cols-4 gap-2"></div>
                        @error('images.*')
                            <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                            Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-full hover:bg-gray-300" style="border-radius: 0.375rem !important;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Product Variants -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Product Variants</h3>
                    <button onclick="document.getElementById('addVariantForm').classList.toggle('hidden')" class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-full hover:bg-blue-600" style="border-radius: 0.375rem !important;">
                        + Add Variant
                    </button>
                </div>

                <!-- Add Variant Form -->
                <div id="addVariantForm" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                    <form method="POST" action="{{ route('admin.products.variants.store', $product) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div>
                                <label for="variant_name" class="block text-xs font-medium text-gray-700 mb-1">Variant Name</label>
                                <input type="text" name="variant_name" id="variant_name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" style="border-radius: 0.375rem !important;">
                            </div>
                            <div>
                                <label for="price" class="block text-xs font-medium text-gray-700 mb-1">Price (₱)</label>
                                <input type="number" name="price" id="price" step="0.01" min="0" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" style="border-radius: 0.375rem !important;">
                            </div>
                            <div>
                                <label for="stock" class="block text-xs font-medium text-gray-700 mb-1">Stock</label>
                                <input type="number" name="stock" id="stock" min="0" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" style="border-radius: 0.375rem !important;">
                            </div>
                            <div>
                                <label for="variant_image" class="block text-xs font-medium text-gray-700 mb-1">Image</label>
                                <input type="file" name="image" id="variant_image" accept="image/*" onchange="previewVariantImage(event, 'add-variant-preview')"
                                    class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" style="border-radius: 0.375rem !important;">
                                <img id="add-variant-preview" src="" alt="Preview" class="hidden mt-1 h-14 w-14 object-cover rounded-lg border border-gray-200">
                            </div>
                        </div>
                        <button type="submit" class="px-3 py-2 bg-blue-500 text-white text-xs font-medium rounded-md hover:bg-blue-600" style="border-radius: 0.375rem !important;">
                            Add Variant
                        </button>
                    </form>
                </div>

                <!-- Variant Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b text-xs uppercase tracking-wide text-gray-500">
                                <th class="py-2 px-4 text-left">Image</th>
                                <th class="py-2 px-4 text-left">Variant Name</th>
                                <th class="py-2 px-4 text-left">Price</th>
                                <th class="py-2 px-4 text-left">Stock</th>
                                <th class="py-2 px-4 text-center" style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->variants as $variant)
                                <tr class="border-b last:border-0" id="variant-{{ $variant->id }}">
                                    <td class="py-2 px-4">
                                        @if ($variant->image)
                                            <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->variant_name }}" class="h-12 w-12 object-cover rounded">
                                        @else
                                            <div class="h-12 w-12 bg-gray-100 rounded flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4">{{ $variant->variant_name }}</td>
                                    <td class="py-2 px-4 font-medium">₱{{ number_format($variant->price, 2) }}</td>
                                    <td class="py-2 px-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $variant->stock > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-900' }}">
                                            {{ $variant->stock }} units
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <button type="button" onclick="editVariant('{{ $variant->id }}')" class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-md hover:bg-blue-100" style="border-radius: 0.375rem !important;">Edit</button>
                                            <form method="POST" action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" data-confirm="Delete variant '{{ $variant->variant_name }}'?" data-confirm-label="Delete" class="inline-flex items-center px-2.5 py-1 bg-red-50 text-red-700 text-xs font-medium rounded-md hover:bg-red-100" style="border-radius: 0.375rem !important;">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <tr id="edit-variant-{{ $variant->id }}" class="hidden border-b bg-gray-50">
                                    <td colspan="5" class="py-3 px-4">
                                        <form method="POST" action="{{ route('admin.products.variants.update', [$product, $variant]) }}" enctype="multipart/form-data" class="space-y-3">
                                            @csrf
                                            @method('PATCH')
                                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Variant Name</label>
                                                    <input type="text" name="variant_name" value="{{ $variant->variant_name }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" style="border-radius: 0.375rem !important;">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Price (₱)</label>
                                                    <input type="number" name="price" step="0.01" min="0" value="{{ $variant->price }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" style="border-radius: 0.375rem !important;">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Stock</label>
                                                    <input type="number" name="stock" min="0" value="{{ $variant->stock }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" style="border-radius: 0.375rem !important;">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">New Image</label>
                                                    <input type="file" name="image" accept="image/*" onchange="previewVariantImage(event, 'edit-variant-preview-{{ $variant->id }}')" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100" style="border-radius: 0.375rem !important;">
                                                    <p class="mt-1 text-[11px] text-gray-500">Optional. Uploading a new image will replace the current one.</p>
                                                    <div class="mt-2 flex items-center gap-2">
                                                        @if ($variant->image)
                                                            <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->variant_name }}" class="h-12 w-12 object-cover rounded border border-gray-200">
                                                        @endif
                                                        <img id="edit-variant-preview-{{ $variant->id }}" src="" alt="Preview" class="hidden h-12 w-12 object-cover rounded border border-gray-200">
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700" style="border-radius: 0.375rem !important;">Save</button>
                                                    <button type="button" onclick="cancelEdit('{{ $variant->id }}')" class="px-3 py-2 bg-gray-200 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-300" style="border-radius: 0.375rem !important;">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">
                                        No variants yet. Add your first variant above.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    function previewImages(event) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            const index = i;
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `<img src="${e.target.result}" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                    <span class="absolute top-1 right-1 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded">${index + 1}</span>`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(files[i]);
        }
    }

    function previewMainImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const preview = document.getElementById('main-image-preview');
        if (!preview) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function previewVariantImage(event, previewId) {
        const file = event.target.files[0];
        if (!file) return;
        const img = document.getElementById(previewId);
        if (!img) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            img.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function editVariant(id) {
        document.querySelectorAll('[id^="variant-"]').forEach(row => row.classList.remove('hidden'));
        document.querySelectorAll('[id^="edit-variant-"]').forEach(row => row.classList.add('hidden'));
        document.getElementById('variant-' + id).classList.add('hidden');
        document.getElementById('edit-variant-' + id)?.classList.remove('hidden');
    }

    function cancelEdit(id) {
        document.getElementById('variant-' + id).classList.remove('hidden');
        document.getElementById('edit-variant-' + id)?.classList.add('hidden');
    }

    function toggleImageDelete(imageId) {
        const checkbox = document.getElementById('delete-image-' + imageId);
        const card = document.getElementById('image-card-' + imageId);
        if (!checkbox || !card) return;

        checkbox.checked = !checkbox.checked;

        if (checkbox.checked) {
            card.classList.add('opacity-40', 'ring-2', 'ring-red-500');
        } else {
            card.classList.remove('opacity-40', 'ring-2', 'ring-red-500');
        }
    }
</script>

@endsection