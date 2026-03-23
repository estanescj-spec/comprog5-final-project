@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Checkout') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h3 class="text-2xl font-bold mb-6">Complete Your Order</h3>

            <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Shipping Address
                    </label>
                    <textarea name="shipping_address" id="shipping_address" rows="3" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                    @error('shipping_address')
                        <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Method
                    </label>
                    <select name="payment_method" id="payment_method"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="COD" @selected(old('payment_method', 'COD') === 'COD')>Cash on Delivery (COD)</option>
                        <option value="GCash" @selected(old('payment_method') === 'GCash')>GCash</option>
                        <option value="Maya" @selected(old('payment_method') === 'Maya')>Maya</option>
                        <option value="card" @selected(old('payment_method') === 'card')>Credit/Debit Card</option>
                        <option value="BankTransfer" @selected(old('payment_method') === 'BankTransfer')>Bank Transfer</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-blue-800">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t pt-6">
                    <h4 class="font-semibold mb-4">Order Summary</h4>
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between text-sm">
                                <span>{{ $item->variant->product->name }} {{ $item->variant->variant_name ? '(' . $item->variant->variant_name . ')' : '' }} x{{ $item->quantity }}</span>
                                <span>₱{{ number_format($item->variant->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t mt-4 pt-4 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-blue-800">₱{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-800 transition-colors">
                        Place Order
                    </button>
                    <a href="{{ route('cart.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Back to Cart
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
