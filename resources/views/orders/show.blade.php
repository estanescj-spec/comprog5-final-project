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

            @php
                $paymentLabels = [
                    'COD' => 'Cash on Delivery (COD)',
                    'GCash' => 'GCash',
                    'Maya' => 'Maya',
                    'card' => 'Credit/Debit Card',
                    'BankTransfer' => 'Bank Transfer',
                ];
            @endphp

            @if($order->payment)
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-1">Payment Details</h4>
                    <p class="text-sm text-gray-700">Method: {{ $paymentLabels[$order->payment->method] ?? $order->payment->method }}</p>
                    <p class="text-sm text-gray-700">Status: {{ ucfirst($order->payment->status) }}</p>
                </div>
            @endif

            <div class="border-t pt-4 mt-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Items</h4>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="border rounded-lg p-3 flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $item->variant->product->name }}
                                    @if($item->variant->variant_name)
                                        <span class="text-gray-600">({{ $item->variant->variant_name }})</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">
                                    Quantity: {{ $item->quantity }} · Unit price: ₱{{ number_format($item->unit_price, 2) }}
                                </p>
                            </div>
                            <div class="text-sm font-semibold text-gray-900">
                                ₱{{ number_format($item->unit_price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center mt-4 pt-4 border-t">
                    <div class="font-bold text-lg">
                        Total: <span class="text-blue-800">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($order->status === 'completed')
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Rate Your Products</h3>

                <div class="space-y-4">
                    @foreach($order->items as $item)
                        @php
                            $product = $item->variant->product;
                            $existingRating = $product->ratings()
                                ->where('user_id', auth()->id())
                                ->first();
                        @endphp
                        <div class="border rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-900 mb-1">
                                {{ $product->name }}
                                @if($item->variant->variant_name)
                                    <span class="text-gray-600">({{ $item->variant->variant_name }})</span>
                                @endif
                            </p>

                            <form method="POST" action="{{ route('products.ratings.store', $product) }}" enctype="multipart/form-data" class="space-y-2 mt-2">
                                @csrf
                                <div class="flex items-center gap-3">
                                    <label class="text-xs font-medium text-gray-700">Rating</label>
                                    <select name="rating" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" @selected(optional($existingRating)->rating === $i)>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Comment (optional)</label>
                                    <textarea
                                        name="comment"
                                        rows="2"
                                        class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    >{{ old('comment', optional($existingRating)->comment) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Photo (optional)</label>
                                    <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-900 hover:file:bg-blue-200">
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                                    {{ $existingRating ? 'Update Rating' : 'Submit Rating' }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center">
            <div class="flex gap-2">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-white text-black border border-black text-sm font-medium rounded-full hover:bg-gray-100" style="border-radius: 0.375rem !important;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to My Orders
                </a>
                @if($order->status === 'pending')
                    <form method="POST" action="{{ route('orders.cancel', $order) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" data-confirm="Cancel this order? This cannot be undone." data-confirm-label="Cancel Order" class="inline-flex items-center px-4 py-2 bg-white text-black border border-black text-sm font-medium rounded-full hover:bg-gray-100" style="border-radius: 0.375rem !important;">
                            Cancel Order
                        </button>
                    </form>
                @endif
            </div>
            <a href="{{ route('orders.receipt', $order) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                </svg>
                Download Receipt PDF
            </a>
        </div>
    </div>
</div>
@endsection

