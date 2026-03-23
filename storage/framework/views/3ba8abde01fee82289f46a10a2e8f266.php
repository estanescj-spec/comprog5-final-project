<?php $__env->startSection('header'); ?>
    <div class="w-full max-w-[95rem] mx-auto">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight text-left mb-0">My Orders</h2>
        <nav class="w-full mt-2 bg-white border-b border-blue-100 shadow-sm mb-0">
            <div class="flex flex-row flex-nowrap w-full overflow-x-auto gap-0">
                <?php
                    $statuses = ['all' => 'All', 'pending' => 'Pending', 'ongoing' => 'Ongoing', 'completed' => 'Completed', 'canceled' => 'Canceled'];
                    $activeStatus = request('status', 'all');
                ?>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('orders.index', $key === 'all' ? [] : ['status' => $key])); ?>"
                       class="flex-1 text-center px-0 py-2 text-sm font-semibold border-b-2 transition
                            <?php echo e($activeStatus === $key ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-blue-700 hover:bg-blue-50'); ?>">
                        <?php echo e($label); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </nav>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="w-full max-w-[95rem] mx-auto sm:px-6 lg:px-8">
        <?php if($orders->isEmpty()): ?>
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-600 mb-4">Start shopping to place your first order.</p>
                <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 9999px !important;">
                    Browse Products
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-2xl shadow-sm p-6 cursor-pointer hover:shadow-md transition-shadow"
                         data-href="<?php echo e(route('orders.show', $order)); ?>"
                         role="link"
                         tabindex="0"
                         onclick="window.location.href=this.dataset.href"
                         onkeydown="if(event.key==='Enter' || event.key===' '){ event.preventDefault(); window.location.href=this.dataset.href; }">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold">Order #<?php echo e($order->id); ?></h3>
                                <p class="text-sm text-gray-600"><?php echo e($order->created_at->format('M d, Y')); ?></p>
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
                            <div class="font-bold">Total: <span class="text-blue-800">₱<?php echo e(number_format($order->total_amount, 2)); ?></span></div>
                            <div class="flex gap-2">
                                <?php if($order->status === 'pending'): ?>
                                    <form method="POST" action="<?php echo e(route('orders.cancel', $order)); ?>" onclick="event.stopPropagation();" onkeydown="event.stopPropagation();">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" onclick="event.stopPropagation();" data-confirm="Cancel this order? This cannot be undone." data-confirm-label="Cancel Order" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200" style="border-radius: 0.375rem !important;">Cancel Order</button>
                                    </form>
                                <?php endif; ?>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/orders/index.blade.php ENDPATH**/ ?>