@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Management') }}
        </h2>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
            + Add Category
        </a>
    </div>
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-6 text-gray-900 space-y-4">
                    <h3 class="text-lg font-semibold">Product Categories</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b text-xs uppercase tracking-wide text-gray-500" style="vertical-align: middle;">
                                    <th class="py-2 px-4 whitespace-nowrap text-left">Category Name</th>
                                    <th class="py-2 px-4 whitespace-nowrap text-left">Description</th>
                                    <th class="py-2 px-4 whitespace-nowrap text-center">Products</th>
                                    <th class="py-2 px-4 whitespace-nowrap text-center" style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr class="border-b last:border-0" style="vertical-align: middle;">
                                        <td class="py-2 px-4">
                                            <div class="font-medium">{{ $category->name }}</div>
                                        </td>

                                        <td class="py-2 px-4">
                                            <div class="text-gray-600 max-w-md">{{ $category->description ?: '—' }}</div>
                                        </td>

                                        <td class="py-2 px-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $category->products_count }} products
                                            </span>
                                        </td>

                                        <td class="py-2 px-4 text-center">
                                            <div class="flex gap-2 justify-center">
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                   class="inline-flex items-center gap-1 px-3 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-100 transition" style="border-radius: 0.375rem !important;">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" data-confirm="Delete category '{{ $category->name }}'? This cannot be undone." data-confirm-label="Delete"
                                                        class="inline-flex items-center gap-1 px-3 py-2 bg-red-50 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-100 transition" style="border-radius: 0.375rem !important;">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500">
                                            No categories found. <a href="{{ route('admin.categories.create') }}" class="text-blue-800 hover:text-blue-900">Add your first category</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $categories->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
