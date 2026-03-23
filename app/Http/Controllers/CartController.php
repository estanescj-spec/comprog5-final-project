<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->carts()->first();
        $cartItems = $cart ? $cart->items()->with(['variant.product.variants'])->get() : collect();
        
        $total = $cartItems->sum(function ($item) {
            return $item->variant->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (empty($validated['product_variant_id']) && empty($validated['product_id'])) {
            return back()->with('error', 'Please select a product or variant.');
        }

        if (!empty($validated['product_variant_id'])) {
            $variant = ProductVariant::findOrFail($validated['product_variant_id']);
        } else {
            $product = Product::with('variants')->findOrFail($validated['product_id']);

            $variant = $product->variants()->first();

            if (! $variant) {
                return back()->with('error', 'This product has no variants available.');
            }
        }

        if ($variant->stock < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = auth()->user()->carts()->firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = $cart->items()->where('product_variant_id', $variant->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            if ($variant->stock < $newQuantity) {
                return back()->with('error', 'Not enough stock available.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variant->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        return back()->with('success', 'Item added to cart successfully.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'product_variant_id' => 'nullable|exists:product_variants,id',
        ]);

        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $variant = $cartItem->variant;

        if (!empty($validated['product_variant_id'])) {
            $variant = ProductVariant::findOrFail($validated['product_variant_id']);

            if ($variant->product_id !== $cartItem->variant->product_id) {
                return back()->with('error', 'You can only switch between variants of the same product.');
            }
        }

        if ($variant->stock < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cartItem->update([
            'product_variant_id' => $variant->id,
            'quantity' => $validated['quantity'],
        ]);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $cart = auth()->user()->carts()->first();
        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('success', 'Cart cleared successfully.');
    }
}
