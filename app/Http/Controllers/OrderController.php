<?php

namespace App\Http\Controllers;

use App\Mail\OrderNotificationMail;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Throwable;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->orders()->with('items.variant.product')->latest();
        $status = $request->query('status');
        if ($status && in_array($status, ['pending', 'ongoing', 'completed', 'canceled'])) {
            $query->where('status', $status);
        }
        $orders = $query->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $order->load('items.variant.product');
        return view('orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $order->loadMissing(['user', 'items.variant.product', 'payment']);

        $pdf = Pdf::loadView('pdf.order-receipt', [
            'order' => $order,
        ])->setOption('defaultFont', 'DejaVu Sans');

        return $pdf->download('receipt-order-' . $order->id . '-' . now()->format('YmdHis') . '.pdf');
    }

    public function update(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($order->status, ['completed', 'canceled'], true)) {
            return back()->with('error', 'Completed or canceled orders can no longer be edited.');
        }

        $validated = $request->validate([
            'shipping_address' => 'required|string|max:500',
        ]);

        $order->update([
            'shipping_address' => $validated['shipping_address'],
        ]);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function create(Request $request)
    {
        // BUY NOW
        if (session()->has('buy_now_variant_id')) {

            $variant = ProductVariant::with('product')
                ->findOrFail(session('buy_now_variant_id'));

            $quantity = session('buy_now_quantity');
            $total = $variant->price * $quantity;

            return view('orders.create', [
                'cartItems' => collect([
                    (object)[
                        'variant' => $variant,
                        'quantity' => $quantity
                    ]
                ]),
                'total' => $total,
                'isBuyNow' => true
            ]);
        }

        $selected = $request->selected_items;

        if (!$selected) {
            return redirect()->route('cart.index')
                ->with('error', 'Please select items.');
        }

        $cart = Auth::user()->carts()->first();

        $cartItems = $cart
            ? $cart->items()
                ->whereIn('id', $selected)
                ->with('variant.product')
                ->get()
            : collect();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'No valid items selected.');
        }

        $total = $cartItems->sum(fn($item) =>
            $item->variant->price * $item->quantity
        );

        session(['checkout_selected_ids' => $selected]);

        return view('orders.create', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'nullable|in:COD,GCash,card,Maya,BankTransfer',
        ]);

        $paymentMethod = $request->payment_method ?? 'COD';

        /*
        |--------------------------------------------------------------------------
        | BUY NOW CHECKOUT
        |--------------------------------------------------------------------------
        */
        if (session()->has('buy_now_variant_id')) {

            $variant = \App\Models\ProductVariant::findOrFail(session('buy_now_variant_id'));
            $quantity = session('buy_now_quantity');

            if ($variant->stock < $quantity) {
                return back()->with('error', 'Not enough stock available.');
            }

            $order = DB::transaction(function () use ($variant, $quantity, $request, $paymentMethod) {

                $total = $variant->price * $quantity;

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'shipping_address' => $request->shipping_address,
                    'status' => 'pending',
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'method' => $paymentMethod,
                    'status' => 'pending',
                    'amount' => $total,
                    'paid_at' => null,
                ]);

                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'unit_price' => $variant->price,
                ]);

                $variant->decrement('stock', $quantity);

                return $order;
            });

            $this->sendOrderEmail(
                $order,
                'Order Placed Successfully',
                'Your transaction has been completed and your order is now pending processing.'
            );

            session()->forget(['buy_now_variant_id', 'buy_now_quantity']);

            return redirect()->route('orders.index')
                ->with('success', 'Order placed successfully!');
        }

        // NORMAL CART CHECKOUT (SELECTED ONLY)

        $selectedIds = session('checkout_selected_ids');

        if (!$selectedIds) {
            return redirect()->route('cart.index')
                ->with('error', 'No selected items found.');
        }

        $cart = Auth::user()->carts()->first();

        $cartItems = $cart
            ? $cart->items()
                ->whereIn('id', $selectedIds)
                ->with('variant.product')
                ->get()
            : collect();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Selected items not found.');
        }

        foreach ($cartItems as $item) {
            if ($item->variant->stock < $item->quantity) {
                return back()->with('error',
                    "Not enough stock for {$item->variant->product->name}."
                );
            }
        }

        $order = DB::transaction(function () use ($cartItems, $request, $paymentMethod) {

            $total = $cartItems->sum(fn($item) =>
                $item->variant->price * $item->quantity
            );

            $order = Order::create([
                'user_id' => Auth::id(),
                'shipping_address' => $request->shipping_address,
                'status' => 'pending',
            ]);

            Payment::create([
                'order_id' => $order->id,
                'method' => $paymentMethod,
                'status' => 'pending',
                'amount' => $total,
                'paid_at' => null,
            ]);

            foreach ($cartItems as $item) {

                $order->items()->create([
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->variant->price,
                ]);

                $item->variant->decrement('stock', $item->quantity);

                // REMOVE ONLY SELECTED ITEMS
                $item->delete();
            }

            return $order;
        });

        $this->sendOrderEmail(
            $order,
            'Order Placed Successfully',
            'Your transaction has been completed and your order is now pending processing.'
        );

        session()->forget('checkout_selected_ids');

        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully!');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($order->status, ['completed', 'canceled'], true)) {
            return back()->with('error', 'Completed or canceled orders can no longer be modified.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->variant->increment('stock', $item->quantity);
            }

            $order->update(['status' => 'canceled']);
        });

        $this->sendOrderEmail(
            $order,
            'Order Cancelled: #' . $order->id,
            'Your order has been cancelled successfully.'
        );

        return back()->with('success', 'Order cancelled successfully.');
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        session([
            'buy_now_variant_id' => $request->product_variant_id,
            'buy_now_quantity' => $request->quantity,
        ]);

        return redirect()->route('orders.create');
    }

    private function sendOrderEmail(Order $order, string $subject, string $message): void
    {
        try {
            $order->loadMissing(['user', 'items.variant.product', 'payment']);

            Mail::to($order->user->email)->send(
                new OrderNotificationMail($order, $subject, $message)
            );
        } catch (Throwable $e) {
            report($e);
        }
    }
}
