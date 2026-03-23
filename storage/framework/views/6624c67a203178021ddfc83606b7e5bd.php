<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Update</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial, Helvetica, sans-serif; color:#111827;">
    <?php
        $paymentLabels = [
            'COD' => 'Cash on Delivery (COD)',
            'GCash' => 'GCash',
            'Maya' => 'Maya',
            'card' => 'Credit/Debit Card',
            'BankTransfer' => 'Bank Transfer',
        ];

    ?>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6; padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px; background:#ffffff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden;">
                    <tr>
                        <td style="padding:24px 26px; background:linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%); color:#ffffff;">
                            <div style="font-size:13px; letter-spacing:0.24em; font-weight:700; opacity:.95;">FLEUR DE PEAU</div>
                            <div style="font-size:24px; font-weight:700; margin-top:8px;">Order Notification</div>
                            <div style="font-size:16px; margin-top:10px; line-height:1.5; opacity:.95;"><?php echo e($messageLine); ?></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:22px 26px 10px 26px;">
                            <div style="font-size:18px; font-weight:700; margin-bottom:12px; color:#1f2937;">Order Summary</div>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="font-size:15px; color:#374151; line-height:1.55;">
                                <tr>
                                    <td style="padding:5px 0;"><strong>Order #:</strong> <?php echo e($order->id); ?></td>
                                    <td style="padding:5px 0; text-align:right;">
                                        <strong>Status:</strong>
                                        <?php if($order->status === 'pending'): ?>
                                            <span style="display:inline-block; padding:3px 8px; border-radius:999px; font-size:12px; font-weight:700; background:#FEF3C7; color:#92400E;">Pending</span>
                                        <?php elseif($order->status === 'ongoing'): ?>
                                            <span style="display:inline-block; padding:3px 8px; border-radius:999px; font-size:12px; font-weight:700; background:#DBEAFE; color:#1E40AF;">Ongoing</span>
                                        <?php elseif($order->status === 'completed'): ?>
                                            <span style="display:inline-block; padding:3px 8px; border-radius:999px; font-size:12px; font-weight:700; background:#D1FAE5; color:#065F46;">Completed</span>
                                        <?php elseif($order->status === 'canceled'): ?>
                                            <span style="display:inline-block; padding:3px 8px; border-radius:999px; font-size:12px; font-weight:700; background:#FEE2E2; color:#991B1B;">Canceled</span>
                                        <?php else: ?>
                                            <span style="display:inline-block; padding:3px 8px; border-radius:999px; font-size:12px; font-weight:700; background:#E5E7EB; color:#374151;"><?php echo e(ucfirst($order->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:5px 0;"><strong>Customer:</strong> <?php echo e($order->user->name); ?></td>
                                    <td style="padding:5px 0; text-align:right;"><strong>Email:</strong> <?php echo e($order->user->email); ?></td>
                                </tr>
                                <?php if($order->payment): ?>
                                    <tr>
                                        <td style="padding:5px 0;"><strong>Payment:</strong> <?php echo e($paymentLabels[$order->payment->method] ?? $order->payment->method); ?></td>
                                        <td style="padding:5px 0; text-align:right;"><strong>Payment Status:</strong> <?php echo e(ucfirst($order->payment->status)); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:10px 26px 8px 26px;">
                            <div style="font-size:18px; font-weight:700; margin-bottom:12px; color:#1f2937;">Items</div>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse; font-size:14px; color:#374151; line-height:1.5;">
                                <thead>
                                    <tr>
                                        <th style="text-align:left; border-bottom:1px solid #e5e7eb; padding:10px 0; font-size:13px;">Product</th>
                                        <th style="text-align:center; border-bottom:1px solid #e5e7eb; padding:10px 0; width:70px; font-size:13px;">Qty</th>
                                        <th style="text-align:right; border-bottom:1px solid #e5e7eb; padding:10px 0; width:140px; font-size:13px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="padding:11px 0; border-bottom:1px solid #f3f4f6;">
                                                <?php echo e($item->variant->product->name ?? 'Product'); ?>

                                                <?php if($item->variant->variant_name): ?>
                                                    <span style="color:#6b7280; font-size:13px;">(<?php echo e($item->variant->variant_name); ?>)</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding:11px 0; border-bottom:1px solid #f3f4f6; text-align:center;"><?php echo e($item->quantity); ?></td>
                                            <td style="padding:11px 0; border-bottom:1px solid #f3f4f6; text-align:right;">₱<?php echo e(number_format((float) ($item->unit_price * $item->quantity), 2)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:14px 26px 24px 26px;">
                            <div style="text-align:right; font-size:20px; font-weight:700; color:#1d4ed8;">
                                Total: ₱<?php echo e(number_format((float) $order->total_amount, 2)); ?>

                            </div>
                            <p style="margin:16px 0 0 0; font-size:14px; color:#6b7280; line-height:1.5;">
                                A PDF receipt is attached to this email for your records.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/emails/orders/notification.blade.php ENDPATH**/ ?>