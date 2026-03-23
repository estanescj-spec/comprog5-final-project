@extends('layouts.app')

@section('header')
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Management') }}
        </h2>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
            + Add Product
        </a>
    </div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
    #products-table_wrapper,
    #trashed-products-table_wrapper {
        font-size: 0.875rem;
    }

    #products-table_wrapper .dataTables_filter,
    #products-table_wrapper .dataTables_length,
    #trashed-products-table_wrapper .dataTables_filter,
    #trashed-products-table_wrapper .dataTables_length {
        margin-bottom: 0.75rem;
        font-size: 0.8rem;
        color: #6b7280;
    }

    #products-table_wrapper .dataTables_filter input,
    #trashed-products-table_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        margin-left: 0.5rem;
        outline: none;
    }

    #products-table_wrapper .dataTables_length select,
    #trashed-products-table_wrapper .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        margin: 0 0.25rem;
    }

    #products-table_wrapper .dataTables_paginate,
    #trashed-products-table_wrapper .dataTables_paginate {
        margin-top: 0.5rem;
    }

    #products-table_wrapper .dataTables_paginate .paginate_button,
    #trashed-products-table_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.375rem !important;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        color: #374151 !important;
        padding: 0.5rem 0.75rem !important;
        margin-left: 0.25rem !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
    }

    #products-table_wrapper .dataTables_paginate .paginate_button.current,
    #trashed-products-table_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: #fff !important;
    }

    #products-table_wrapper .dataTables_paginate .paginate_button:hover,
    #trashed-products-table_wrapper .dataTables_paginate .paginate_button:hover {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: #fff !important;
    }

    #products-table_wrapper .dataTables_info,
    #trashed-products-table_wrapper .dataTables_info {
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #6b7280;
    }

    #products-table thead th,
    #trashed-products-table thead th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    #products-table tbody tr:hover,
    #trashed-products-table tbody tr:hover {
        background: #f8fbff;
    }

    @media (max-width: 768px) {
        #products-table_wrapper .dataTables_filter,
        #products-table_wrapper .dataTables_length,
        #trashed-products-table_wrapper .dataTables_filter,
        #trashed-products-table_wrapper .dataTables_length {
            float: none;
            text-align: left;
            width: 100%;
        }

        #products-table_wrapper .dataTables_filter input,
        #trashed-products-table_wrapper .dataTables_filter input {
            width: 100%;
            margin-left: 0;
            margin-top: 0.35rem;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
let productsTable;
let trashedProductsTable;

$(document).ready(function () {
    productsTable = $('#products-table').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        columnDefs: [
            { orderable: false, targets: [0, 8] }
        ],
        language: {
            search: 'Search products:',
            lengthMenu: 'Show _MENU_ products',
            info: 'Showing _START_ to _END_ of _TOTAL_ products',
            emptyTable: 'No products found',
            zeroRecords: 'No matching products found',
        }
    });

    trashedProductsTable = $('#trashed-products-table').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        columnDefs: [
            { orderable: false, targets: [0, 7] }
        ],
        language: {
            search: 'Search trash:',
            lengthMenu: 'Show _MENU_ trashed products',
            info: 'Showing _START_ to _END_ of _TOTAL_ trashed products',
            emptyTable: 'No trashed products found',
            zeroRecords: 'No matching trashed products found',
        }
    });
});

function showProductTab(tab) {
    const activeTable = document.getElementById('active-products-section');
    const trashTable = document.getElementById('trashed-products-section');
    const activeBtn = document.getElementById('tab-active-products');
    const trashBtn = document.getElementById('tab-trashed-products');

    if (tab === 'trash') {
        activeTable.classList.add('hidden');
        trashTable.classList.remove('hidden');
        activeBtn.classList.remove('bg-blue-500', 'text-white');
        activeBtn.classList.add('bg-gray-100', 'text-gray-700');
        trashBtn.classList.remove('bg-gray-100', 'text-gray-700');
        trashBtn.classList.add('bg-blue-500', 'text-white');

        if (trashedProductsTable) {
            trashedProductsTable.columns.adjust().draw(false);
        }
    } else {
        trashTable.classList.add('hidden');
        activeTable.classList.remove('hidden');
        trashBtn.classList.remove('bg-blue-500', 'text-white');
        trashBtn.classList.add('bg-gray-100', 'text-gray-700');
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        activeBtn.classList.add('bg-blue-500', 'text-white');

        if (productsTable) {
            productsTable.columns.adjust().draw(false);
        }
    }
}
</script>
@endsection

@section('content')

    <div class="py-12">
        <div class="w-full max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <h3 class="text-lg font-semibold">FLEUR DE PEAU Products</h3>

                        <form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2">
                            @csrf
                            <input type="file" name="import_file" accept=".xlsx,.xls,.csv" required
                                class="text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100" style="border-radius: 0.375rem !important;">
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-full hover:bg-emerald-700" style="border-radius: 0.375rem !important;">
                                Import Excel
                            </button>
                        </form>
                    </div>

                    <div class="text-xs text-gray-500">
                        Import columns: <span class="font-medium">name</span>, <span class="font-medium">description</span>, <span class="font-medium">categories</span> (comma-separated), <span class="font-medium">variant_name</span>, <span class="font-medium">price</span>, <span class="font-medium">stock</span>.
                    </div>

                    <div class="flex items-center gap-2 border-b pb-3">
                        <button id="tab-active-products" type="button" onclick="showProductTab('active')" class="px-3 py-1.5 rounded-full text-xs font-medium bg-blue-500 text-white" style="border-radius: 0.375rem !important;">
                            Active ({{ $products->count() }})
                        </button>
                        <button id="tab-trashed-products" type="button" onclick="showProductTab('trash')" class="px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700" style="border-radius: 0.375rem !important;">
                            Trash ({{ $trashedProducts->count() }})
                        </button>
                    </div>

                    <div id="active-products-section" class="overflow-x-auto">
                        <table id="products-table" class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b text-xs uppercase tracking-wide text-gray-500" style="vertical-align: middle;">
                                    <th class="py-2 px-4 whitespace-nowrap" style="width: 10%;">Image</th>
                                    <th class="py-2 px-4 whitespace-nowrap text-left">Name</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Category</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Base Price</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Variants</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Avg Rating</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Sold</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Created</th>
                                    <th class="py-2 px-4 whitespace-nowrap" style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr class="border-b last:border-0" style="vertical-align: middle; text-align: center;">
                                        <td class="py-2 px-4 whitespace-nowrap">
                                            @php
                                                $adminCardImage = ($product->images && $product->images->isNotEmpty())
                                                    ? asset('storage/' . $product->images->first()->image_path)
                                                    : ($product->variants->firstWhere('image', '!=', null)?->image
                                                        ? asset('storage/' . $product->variants->firstWhere('image', '!=', null)->image)
                                                        : null);
                                            @endphp
                                            @if ($adminCardImage)
                                                <div class="h-12 w-12 overflow-hidden rounded-md flex-shrink-0 mx-auto">
                                                    <img src="{{ $adminCardImage }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                                </div>
                                            @else
                                                <div class="h-12 w-12 rounded-md bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0 mx-auto">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="py-2 px-4 text-left">
                                            <div class="font-medium">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</div>
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-900">
                                                {{ $product->categories->pluck('name')->join(', ') ?: 'No Category' }}
                                            </span>
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap font-medium">
                                            ₱{{ number_format($product->variants->min('price') ?? 0, 2) }}
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap">
                                            <span class="text-gray-600">{{ $product->variants_count }}</span>
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap">
                                            @if (!is_null($product->ratings_avg_rating))
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                                    ★ {{ number_format((float) $product->ratings_avg_rating, 1) }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">No ratings</span>
                                            @endif
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap font-medium text-gray-700">
                                            {{ (int) ($product->units_sold ?? 0) }}
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap text-xs text-gray-500">
                                            {{ $product->created_at->format('M d, Y') }}
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-2 justify-center">
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="inline-flex items-center gap-1 px-3 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-100 transition" style="border-radius: 0.375rem !important;">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" data-confirm="Move '{{ $product->name }}' to trash?" data-confirm-label="Move to Trash"
                                                        class="inline-flex items-center gap-1 px-3 py-2 bg-red-50 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-100 transition" style="border-radius: 0.375rem !important;">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Trash
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="trashed-products-section" class="overflow-x-auto hidden">
                        <table id="trashed-products-table" class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b text-xs uppercase tracking-wide text-gray-500" style="vertical-align: middle;">
                                    <th class="py-2 px-4 whitespace-nowrap" style="width: 10%;">Image</th>
                                    <th class="py-2 px-4 whitespace-nowrap text-left">Name</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Category</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Variants</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Avg Rating</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Sold</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Deleted</th>
                                    <th class="py-2 px-4 whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashedProducts as $product)
                                    <tr class="border-b last:border-0" style="vertical-align: middle; text-align: center;">
                                        <td class="py-2 px-4 whitespace-nowrap">
                                            @php
                                                $trashedCardImage = ($product->images && $product->images->isNotEmpty())
                                                    ? asset('storage/' . $product->images->first()->image_path)
                                                    : ($product->variants->firstWhere('image', '!=', null)?->image
                                                        ? asset('storage/' . $product->variants->firstWhere('image', '!=', null)->image)
                                                        : null);
                                            @endphp
                                            @if ($trashedCardImage)
                                                <div class="h-12 w-12 overflow-hidden rounded-md flex-shrink-0 mx-auto">
                                                    <img src="{{ $trashedCardImage }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                                </div>
                                            @else
                                                <div class="h-12 w-12 rounded-md bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0 mx-auto">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 text-left">
                                            <div class="font-medium">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</div>
                                        </td>
                                        <td class="py-2 px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-900">
                                                {{ $product->categories->pluck('name')->join(', ') ?: 'No Category' }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 whitespace-nowrap">{{ $product->variants_count }}</td>

                                        <td class="py-2 px-4 whitespace-nowrap">
                                            @if (!is_null($product->ratings_avg_rating))
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                                    ★ {{ number_format((float) $product->ratings_avg_rating, 1) }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">No ratings</span>
                                            @endif
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap font-medium text-gray-700">{{ (int) ($product->units_sold ?? 0) }}</td>

                                        <td class="py-2 px-4 whitespace-nowrap text-xs text-gray-500">{{ optional($product->deleted_at)->format('M d, Y h:i A') }}</td>
                                        <td class="py-2 px-4 whitespace-nowrap">
                                            <form method="POST" action="{{ route('admin.products.restore', $product->id) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-lg hover:bg-emerald-100 transition" style="border-radius: 0.375rem !important;">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    Restore
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
