@extends('layouts.app')


@section('header')
    <div class="w-full max-w-[95rem] mx-auto">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight text-left mb-0">My Orders</h2>
        <nav class="w-full mt-2 bg-white border-b border-blue-100 shadow-sm mb-0">
            <div class="flex flex-row flex-nowrap w-full overflow-x-auto gap-0">
                @php
                    $statuses = ['all' => 'All', 'pending' => 'Pending', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'canceled' => 'Canceled'];
                    $activeStatus = request('status', 'all');
                @endphp
                @foreach($statuses as $key => $label)
                    <a href="{{ route('orders.index', $key === 'all' ? [] : ['status' => $key]) }}"
                       class="flex-1 text-center px-0 py-2 text-sm font-semibold border-b-2 transition
                            {{ $activeStatus === $key ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-blue-700 hover:bg-blue-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </nav>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="w-full max-w-[95rem] mx-auto sm:px-6 lg:px-8">
        @if($orders->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-600 mb-4">Start shopping to place your first order.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 9999px !important;">
                    Browse Products
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm p-6 cursor-pointer hover:shadow-md transition-shadow"
                         data-href="{{ route('orders.show', $order) }}"
                         role="link"
                         tabindex="0"
                         onclick="window.location.href=this.dataset.href"
                         onkeydown="if(event.key==='Enter' || event.key===' '){ event.preventDefault(); window.location.href=this.dataset.href; }">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold">Order #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }}</p>
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
                            <div class="font-bold">Total: <span class="text-blue-800">₱{{ number_format($order->total_amount, 2) }}</span></div>
                            <div class="flex gap-2">
                                @if($order->status === 'pending')
                                    <form method="POST" action="{{ route('orders.cancel', $order) }}" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="event.stopPropagation();" data-confirm="Cancel this order? This cannot be undone." data-confirm-label="Cancel Order" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200" style="border-radius: 0.375rem !important;">Cancel Order</button>
                                    </form>
                                @endif
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
