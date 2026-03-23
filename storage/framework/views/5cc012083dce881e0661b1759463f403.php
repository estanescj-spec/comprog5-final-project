<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <?php echo e(__('Order Details')); ?> #<?php echo e($order->id); ?>

    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-semibold text-lg">Order #<?php echo e($order->id); ?></h3>
                    <p class="text-sm text-gray-600">
                        Placed on <?php echo e($order->created_at->format('M d, Y H:i')); ?>

                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        Customer: <?php echo e($order->user->name); ?> · <?php echo e($order->user->email); ?>

                    </p>
                </div>
                <span class="text-base font-semibold text-black">
                    <?php echo e(ucfirst($order->status)); ?>

                </span>
            </div>

            <div class="mb-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Shipping Address</h4>
                <p class="text-sm text-gray-700 whitespace-pre-line">
                    <?php echo e($order->shipping_address); ?>

                </p>
            </div>

            <div class="border-t pt-4 mt-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Items</h4>
                <div class="space-y-2">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between text-sm">
                            <span><?php echo e($item->variant->product->name); ?> <?php echo e($item->variant->variant_name ? '(' . $item->variant->variant_name . ')' : ''); ?> x<?php echo e($item->quantity); ?></span>
                            <span>₱<?php echo e(number_format($item->unit_price * $item->quantity, 2)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="flex justify-between items-center mt-4 pt-4 border-t">
                    <div class="font-bold text-lg">
                        Total: <span class="text-blue-800">₱<?php echo e(number_format($order->total_amount, 2)); ?></span>
                    </div>
                    <?php if($order->payment): ?>
                        <?php
                            $paymentLabels = [
                                'COD' => 'Cash on Delivery (COD)',
                                'GCash' => 'GCash',
                                'Maya' => 'Maya',
                                'card' => 'Credit/Debit Card',
                                'BankTransfer' => 'Bank Transfer',
                            ];
                        ?>
                        <div class="text-xs text-gray-500 text-right">
                            <div>Payment Method: <?php echo e($paymentLabels[$order->payment->method] ?? $order->payment->method); ?></div>
                            <div>Status: <?php echo e(ucfirst($order->payment->status)); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Manage Order</h3>

            <?php if($order->status === 'completed'): ?>
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">
                    This order is completed and can no longer be edited.
                </div>
            <?php elseif($order->status === 'canceled'): ?>
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-800">
                    This order is canceled and can no longer be edited.
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('admin.orders.update', $order)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Order Status</label>
                        <select
                            id="status"
                            name="status"
                            <?php if(in_array($order->status, ['completed', 'canceled'])): echo 'disabled'; endif; ?>
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        >
                            <option value="">No change</option>
                            <option value="pending" <?php if($order->status === 'pending'): echo 'selected'; endif; ?>>Pending</option>
                            <option value="ongoing" <?php if($order->status === 'ongoing'): echo 'selected'; endif; ?>>Ongoing</option>
                            <option value="completed" <?php if($order->status === 'completed'): echo 'selected'; endif; ?>>Completed</option>
                            <option value="canceled" <?php if($order->status === 'canceled'): echo 'selected'; endif; ?>>Canceled</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-xs text-blue-800"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Shipping Address</label>
                        <div class="mt-1 block w-full border border-gray-200 bg-gray-50 text-gray-700 text-sm px-3 py-2" style="min-height: 84px;">
                            <?php echo e($order->shipping_address); ?>

                        </div>
                        <p class="mt-1 text-xs text-gray-500">Shipping address is customer-provided and cannot be edited by admin.</p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t mt-2">
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center gap-1 px-4 py-2 bg-white text-black border border-black text-sm font-medium rounded-full hover:bg-gray-100" style="border-radius: 0.375rem !important;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Orders
                    </a>
                    <?php if(!in_array($order->status, ['completed', 'canceled'])): ?>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                            Save Changes
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>