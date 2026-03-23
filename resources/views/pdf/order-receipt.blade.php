<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Receipt</title>
    <style>
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
            padding: 0;
        }
        .page {
            padding: 26px;
        }
        .header {
            border: 1px solid #dbeafe;
            background: #eff6ff;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }
        .brand {
            font-size: 19px;
            font-weight: 700;
            margin: 0;
            color: #1d4ed8;
        }
        .subtitle {
            margin-top: 4px;
            color: #6b7280;
            font-size: 11px;
        }
        .status {
            display: inline-block;
            margin-top: 8px;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            background: #e5e7eb;
            color: #374151;
        }
        h2 {
            font-size: 13px;
            margin: 16px 0 8px;
            color: #111827;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .meta-table td {
            border: 1px solid #e5e7eb;
            padding: 7px 8px;
            vertical-align: top;
        }
        .meta-label {
            color: #6b7280;
            font-size: 10px;
            margin-bottom: 2px;
        }
        .meta-value {
            font-weight: 600;
            color: #111827;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.items th,
        table.items td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }
        table.items th {
            background: #f3f4f6;
            font-size: 11px;
        }
        .right { text-align: right; }
        .summary {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        .summary td {
            border: none;
            padding: 4px 0;
            font-size: 12px;
        }
        .total {
            font-size: 15px;
            font-weight: 700;
            color: #1d4ed8;
        }
        .footer-note {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 10px;
        }
    </style>
</head>
<body>
    @php
        $paymentLabels = [
            'COD' => 'Cash on Delivery (COD)',
            'GCash' => 'GCash',
            'Maya' => 'Maya',
            'card' => 'Credit/Debit Card',
            'BankTransfer' => 'Bank Transfer',
        ];

    @endphp

    <div class="page">
        <div class="header">
            <p class="brand">FLEUR DE PEAU</p>
            <div class="subtitle">Official Order Receipt</div>
            @if($order->status === 'pending')
                <div class="status" style="background:#FEF3C7; color:#92400E;">PENDING</div>
            @elseif($order->status === 'ongoing')
                <div class="status" style="background:#DBEAFE; color:#1E40AF;">ONGOING</div>
            @elseif($order->status === 'completed')
                <div class="status" style="background:#D1FAE5; color:#065F46;">COMPLETED</div>
            @elseif($order->status === 'canceled')
                <div class="status" style="background:#FEE2E2; color:#991B1B;">CANCELED</div>
            @else
                <div class="status">{{ strtoupper($order->status) }}</div>
            @endif
        </div>

        <h2>Order & Customer Details</h2>
        <table class="meta-table">
            <tr>
                <td width="50%">
                    <div class="meta-label">Order ID</div>
                    <div class="meta-value">#{{ $order->id }}</div>
                </td>
                <td width="50%">
                    <div class="meta-label">Order Date</div>
                    <div class="meta-value">{{ $order->created_at?->format('Y-m-d H:i') }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="meta-label">Customer</div>
                    <div class="meta-value">{{ $order->user->name }}</div>
                </td>
                <td>
                    <div class="meta-label">Email</div>
                    <div class="meta-value">{{ $order->user->email }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="meta-label">Payment Method</div>
                    <div class="meta-value">{{ $order->payment ? ($paymentLabels[$order->payment->method] ?? $order->payment->method) : 'N/A' }}</div>
                </td>
                <td>
                    <div class="meta-label">Payment Status</div>
                    <div class="meta-value">{{ ucfirst($order->payment?->status ?? 'pending') }}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="meta-label">Shipping Address</div>
                    <div class="meta-value">{{ $order->shipping_address }}</div>
                </td>
            </tr>
        </table>

        <h2>Purchased Items</h2>
        <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Variant</th>
                <th class="right">Qty</th>
                <th class="right">Unit Price</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->variant->product->name ?? 'Product' }}</td>
                    <td>{{ $item->variant->variant_name ?: 'Default Variant' }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">₱{{ number_format((float) $item->unit_price, 2) }}</td>
                    <td class="right">₱{{ number_format((float) ($item->unit_price * $item->quantity), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        </table>

        <table class="summary">
            <tr>
                <td class="right">Subtotal: ₱{{ number_format((float) $order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="right total">Total Amount: ₱{{ number_format((float) $order->total_amount, 2) }}</td>
            </tr>
        </table>

        <div class="footer-note">
            This is a system-generated receipt. Keep this document for your records.
        </div>
    </div>
</body>
</html>
