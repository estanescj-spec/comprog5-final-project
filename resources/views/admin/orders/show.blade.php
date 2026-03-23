@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Order Details') }} #{{ $order->id }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-semibold text-lg">Order #{{ $order->id }}</h3>
                    <p class="text-sm text-gray-600">
                        Placed on {{ $order->created_at->format('M d, Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        Customer: {{ $order->user->name }} · {{ $order->user->email }}
                    </p>
                </div>
                <span class="text-base font-semibold text-black">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Shipping Address</h4>
                <p class="text-sm text-gray-700 whitespace-pre-line">
                    {{ $order->shipping_address }}
                </p>
            </div>

            <div class="border-t pt-4 mt-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Items</h4>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item->variant->product->name }} {{ $item->variant->variant_name ? '(' . $item->variant->variant_name . ')' : '' }} x{{ $item->quantity }}</span>
                            <span>₱{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center mt-4 pt-4 border-t">
                    <div class="font-bold text-lg">
                        Total: <span class="text-blue-800">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    @if($order->payment)
                        @php
                            $paymentLabels = [
                                'COD' => 'Cash on Delivery (COD)',
                                'GCash' => 'GCash',
                                'Maya' => 'Maya',
                                'card' => 'Credit/Debit Card',
                                'BankTransfer' => 'Bank Transfer',
                            ];
                        @endphp
                        <div class="text-xs text-gray-500 text-right">
                            <div>Payment Method: {{ $paymentLabels[$order->payment->method] ?? $order->payment->method }}</div>
                            <div>Status: {{ ucfirst($order->payment->status) }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Manage Order</h3>

            @if($order->status === 'completed')
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">
                    This order is completed and can no longer be edited.
                </div>
            @elseif($order->status === 'canceled')
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-800">
                    This order is canceled and can no longer be edited.
                </div>
            @endif

            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Order Status</label>
                        <select
                            id="status"
                            name="status"
                            @disabled(in_array($order->status, ['completed', 'canceled']))
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                            <option value="">No change</option>
                            <option value="pending" @selected($order->status === 'pending')>Pending</option>
                            <option value="ongoing" @selected($order->status === 'ongoing')>Ongoing</option>
                            <option value="completed" @selected($order->status === 'completed')>Completed</option>
                            <option value="canceled" @selected($order->status === 'canceled')>Canceled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-blue-800">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Shipping Address</label>
                        <div class="mt-1 block w-full border border-gray-200 bg-gray-50 text-gray-700 text-sm px-3 py-2" style="min-height: 84px;">
                            {{ $order->shipping_address }}
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Shipping address is customer-provided and cannot be edited by admin.</p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t mt-2">
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-white text-black border border-black text-sm font-medium rounded-full hover:bg-gray-100" style="border-radius: 0.375rem !important;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Orders
                    </a>
                    @if(!in_array($order->status, ['completed', 'canceled']))
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                            Save Changes
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

