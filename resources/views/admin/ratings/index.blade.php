@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage Reviews') }}
    </h2>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <style>
        #ratingsTable_wrapper .dataTables_filter input,
        #ratingsTable_wrapper .dataTables_length select,
        #ratingsTable_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }

        #ratingsTable_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #e5e7eb !important;
            background: #fff !important;
            color: #374151 !important;
            margin-left: 0.25rem !important;
        }

        #ratingsTable_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: #fff !important;
        }

        #ratingsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: #fff !important;
        }

        #ratingsTable {
            width: 100% !important;
        }

        .stars-display {
            color: #f59e0b;
            font-size: 0.875rem;
        }

        .comment-cell {
            max-width: 300px;
            white-space: normal;
            word-wrap: break-word;
        }
    </style>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">All Reviews</h3>
                <p class="text-sm text-gray-600">
                    View and manage customer feedback for your skincare products.
                </p>
            </div>

            <div class="p-6 overflow-x-auto">
                @if($ratings->isNotEmpty())
                    <table id="ratingsTable" class="min-w-full text-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Photo</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ratings as $rating)
                                <tr>
                                    <td class="font-medium text-gray-900">{{ $rating->product->name }}</td>
                                    <td class="text-gray-700">{{ $rating->user->name }}</td>
                                    <td>
                                        <span class="stars-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                {!! $i <= $rating->rating ? '★' : '☆' !!}
                                            @endfor
                                            <span class="ml-1 text-xs text-gray-500">({{ $rating->rating }}/5)</span>
                                        </span>
                                    </td>
                                    <td class="comment-cell text-sm text-gray-700">{{ $rating->comment ?: '—' }}</td>
                                    <td>
                                        @if($rating->photo_path)
                                            <img src="{{ asset('storage/' . $rating->photo_path) }}" alt="Review photo" class="h-12 w-12 object-cover rounded-lg border border-gray-200">
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="text-xs text-gray-500">{{ $rating->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.ratings.destroy', $rating) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" data-confirm="Delete this review by {{ $rating->user->name }}?" data-confirm-label="Delete"
                                                class="inline-flex items-center gap-1 px-3 py-2 bg-red-50 text-red-700 text-sm font-semibold rounded hover:bg-red-100 transition" style="border-radius: 0.375rem !important;">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No reviews yet</h3>
                        <p class="text-gray-600">Customer reviews will appear here once they start rating products.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ratingsTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            language: {
                search: 'Search reviews:',
                lengthMenu: 'Show _MENU_ reviews',
                info: 'Showing _START_ to _END_ of _TOTAL_ reviews',
                emptyTable: 'No reviews found',
                zeroRecords: 'No matching reviews found',
            },
            columnDefs: [
                { targets: [4, 6], orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection

