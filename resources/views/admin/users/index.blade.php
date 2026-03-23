@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<style>
    #users-table_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        margin-left: 0.5rem;
        outline: none;
    }
    #users-table_wrapper .dataTables_filter input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.2);
    }
    #users-table_wrapper .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        margin: 0 0.25rem;
    }
    #users-table_wrapper .dataTables_info,
    #users-table_wrapper .dataTables_length,
    #users-table_wrapper .dataTables_filter {
        font-size: 0.8rem;
        color: #6b7280;
        padding: 0.5rem 0;
    }
    #users-table_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.75rem !important;
        border-radius: 0.375rem !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        cursor: pointer;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        color: #374151 !important;
        margin-left: 0.25rem !important;
    }
    #users-table_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        color: #fff !important;
        border: 1px solid #3b82f6 !important;
    }
    #users-table_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #3b82f6 !important;
        color: #fff !important;
        border: 1px solid #3b82f6 !important;
    }
    table.dataTable thead th {
        background-color: #f9fafb;
    }
    table.dataTable tbody tr:hover {
        background-color: #f0f4ff;
    }
</style>
@endsection

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Customer Accounts') }}
    </h2>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    $('#users-table').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        columnDefs: [
            { orderable: false, targets: [0, 3, 4, 5] }
        ],
        language: {
            search: 'Search users:',
            lengthMenu: 'Show _MENU_ users',
            info: 'Showing _START_ to _END_ of _TOTAL_ users',
            emptyTable: 'No users found',
            zeroRecords: 'No matching users found',
        }
    });
});
</script>
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-6 text-gray-900 space-y-4">
                    <h3 class="text-lg font-semibold">FLEUR DE PEAU Customers</h3>

                    <div class="overflow-x-auto">
                        <table id="users-table" class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b text-xs uppercase tracking-wide text-gray-500" style="vertical-align: middle;">
                                    <th class="py-2 px-4 whitespace-nowrap" style="width: 10%;">Profile</th>
                                    <th class="py-2 px-4 whitespace-nowrap" >Name</th>
                                    <th class="py-2 px-4 whitespace-nowrap" >Email</th>
                                    <th class="py-2 px-4 whitespace-nowrap" style="width: 10%;">Role</th>
                                    <th class="py-2 px-4 whitespace-nowrap" style="width: 10%;">Status</th>
                                    <th class="py-2 px-4 whitespace-nowrap" style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-b last:border-0 items-center justify-center" style="vertical-align: middle; text-align: center;">
                                        <td class="py-2 px-4 whitespace-nowrap">
                                            @if ($user->profile_photo_path)
                                                <div class="h-8 w-8 overflow-hidden rounded-md flex-shrink-0 mx-auto">
                                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" class="h-full w-full object-cover">
                                                </div>
                                            @else
                                                <div class="h-8 w-8 rounded-md bg-blue-100 flex items-center justify-center text-[10px] font-semibold text-blue-900 flex-shrink-0 mx-auto">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap" >{{ $user->name }}</td>

                                        <td class="py-2 px-4 whitespace-nowrap">{{ $user->email }}</td>

                                        <td class="py-2 px-4 whitespace-nowrap">
                                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" onchange="this.form.submit()" class="border-gray-300 rounded-md text-xs" style="border-radius: 0.375rem !important;">
                                                    <option value="customer" @selected($user->role === 'customer')>Customer</option>
                                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                                </select>
                                            </form>
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap">
                                            <form method="POST" action="{{ route('admin.users.updateStatus', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <select name="is_active" onchange="this.form.submit()" class="border-gray-300 rounded-md text-xs {{ $user->is_active ? 'text-emerald-700' : 'text-blue-900' }}" style="border-radius: 0.375rem !important;">
                                                    <option value="1" @selected($user->is_active)>Active</option>
                                                    <option value="0" @selected(! $user->is_active)>Deactivated</option>
                                                </select>
                                            </form>
                                        </td>

                                        <td class="py-2 px-4 whitespace-nowrap">
                                            @if (auth()->id() !== $user->id)
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" data-confirm="Delete account '{{ $user->name }}'? This cannot be undone." data-confirm-label="Delete"
                                                        class="inline-flex items-center gap-1 px-3 py-2 bg-red-50 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-100 transition" style="border-radius: 0.375rem !important;">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400 italic">You</span>
                                            @endif
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