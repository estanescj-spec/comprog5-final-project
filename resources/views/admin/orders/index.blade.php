@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage Orders') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($orders->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-600 mb-4">Orders will appear here once customers start placing them.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold">Order #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-600">
                                    {{ $order->created_at->format('M d, Y H:i') }} ·
                                    {{ $order->user->name }} ({{ $order->user->email }})
                                </p>
                            </div>
                            <span class="text-base font-semibold text-black">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span>{{ $item->variant->product->name }} {{ $item->variant->variant_name ? '(' . $item->variant->variant_name . ')' : '' }} x{{ $item->quantity }}</span>
                                    <span>₱{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-between items-center border-t pt-4">
                            <div class="space-y-1">
                                <div class="font-bold">Total: <span class="text-blue-800">₱{{ number_format($order->total_amount, 2) }}</span></div>
                                @if($order->payment)
                                    <p class="text-xs text-gray-500">
                                        Payment: {{ $order->payment->method }} ({{ ucfirst($order->payment->status) }})
                                    </p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-3 py-1.5 bg-white text-black border border-black text-xs font-medium rounded-lg hover:bg-gray-100 transition" style="border-radius: 0.375rem !important;">
                                    {{ in_array($order->status, ['completed', 'canceled']) ? 'View Order' : 'Update Order' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

