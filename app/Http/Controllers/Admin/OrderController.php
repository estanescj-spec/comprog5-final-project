<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderNotificationMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.variant.product', 'payment'])->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.variant.product', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if (in_array($order->status, ['completed', 'canceled'], true)) {
            return back()->with('error', 'Completed or canceled orders are locked and cannot be edited.');
        }

        $validated = $request->validate([
            'status' => 'nullable|in:pending,ongoing,completed,canceled',
        ]);

        $originalStatus = $order->status;

        if (array_key_exists('status', $validated) && $validated['status'] !== null) {
            $order->status = $validated['status'];
        }

        $order->save();

        if ($originalStatus !== 'canceled' && $order->status === 'canceled') {
            $this->sendOrderUpdateEmail(
                $order,
                'Order Cancelled by Admin: #' . $order->id,
                'Your order has been cancelled by the administrator.'
            );
        } else {
            $this->sendOrderUpdateEmail(
                $order,
                'Order Updated: #' . $order->id,
                'Your transaction has been updated by the administrator. Current status: ' . ucfirst($order->status) . '.'
            );
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    private function sendOrderUpdateEmail(Order $order, string $subject, string $message): void
    {
        try {
            $order->loadMissing(['user', 'items.variant.product', 'payment']);

            Mail::to($order->user->email)->send(
                new OrderNotificationMail(
                    $order,
                    $subject,
                    $message
                )
            );
        } catch (Throwable $e) {
            report($e);
        }
    }
}

