<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <?php echo e(__('Manage Orders')); ?>

    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <?php if($orders->isEmpty()): ?>
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-600 mb-4">Orders will appear here once customers start placing them.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold">Order #<?php echo e($order->id); ?></h3>
                                <p class="text-sm text-gray-600">
                                    <?php echo e($order->created_at->format('M d, Y H:i')); ?> ·
                                    <?php echo e($order->user->name); ?> (<?php echo e($order->user->email); ?>)
                                </p>
                            </div>
                            <span class="text-base font-semibold text-black">
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex justify-between text-sm">
                                    <span><?php echo e($item->variant->product->name); ?> <?php echo e($item->variant->variant_name ? '(' . $item->variant->variant_name . ')' : ''); ?> x<?php echo e($item->quantity); ?></span>
                                    <span>₱<?php echo e(number_format($item->unit_price * $item->quantity, 2)); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="flex justify-between items-center border-t pt-4">
                            <div class="space-y-1">
                                <div class="font-bold">Total: <span class="text-blue-800">₱<?php echo e(number_format($order->total_amount, 2)); ?></span></div>
                                <?php if($order->payment): ?>
                                    <p class="text-xs text-gray-500">
                                        Payment: <?php echo e($order->payment->method); ?> (<?php echo e(ucfirst($order->payment->status)); ?>)
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="inline-flex items-center px-3 py-1.5 bg-white text-black border border-black text-xs font-medium rounded-lg hover:bg-gray-100 transition" style="border-radius: 0.375rem !important;">
                                    <?php echo e(in_array($order->status, ['completed', 'canceled']) ? 'View Order' : 'Update Order'); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-6">
                <?php echo e($orders->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>