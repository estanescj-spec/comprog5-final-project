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

            <?php
                $paymentLabels = [
                    'COD' => 'Cash on Delivery (COD)',
                    'GCash' => 'GCash',
                    'Maya' => 'Maya',
                    'card' => 'Credit/Debit Card',
                    'BankTransfer' => 'Bank Transfer',
                ];
            ?>

            <?php if($order->payment): ?>
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-1">Payment Details</h4>
                    <p class="text-sm text-gray-700">Method: <?php echo e($paymentLabels[$order->payment->method] ?? $order->payment->method); ?></p>
                    <p class="text-sm text-gray-700">Status: <?php echo e(ucfirst($order->payment->status)); ?></p>
                </div>
            <?php endif; ?>

            <div class="border-t pt-4 mt-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Items</h4>
                <div class="space-y-3">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg p-3 flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    <?php echo e($item->variant->product->name); ?>

                                    <?php if($item->variant->variant_name): ?>
                                        <span class="text-gray-600">(<?php echo e($item->variant->variant_name); ?>)</span>
                                    <?php endif; ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Quantity: <?php echo e($item->quantity); ?> · Unit price: ₱<?php echo e(number_format($item->unit_price, 2)); ?>

                                </p>
                            </div>
                            <div class="text-sm font-semibold text-gray-900">
                                ₱<?php echo e(number_format($item->unit_price * $item->quantity, 2)); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="flex justify-between items-center mt-4 pt-4 border-t">
                    <div class="font-bold text-lg">
                        Total: <span class="text-blue-800">₱<?php echo e(number_format($order->total_amount, 2)); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php if($order->status === 'completed'): ?>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Rate Your Products</h3>

                <div class="space-y-4">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $product = $item->variant->product;
                            $existingRating = $product->ratings()
                                ->where('user_id', auth()->id())
                                ->first();
                        ?>
                        <div class="border rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-900 mb-1">
                                <?php echo e($product->name); ?>

                                <?php if($item->variant->variant_name): ?>
                                    <span class="text-gray-600">(<?php echo e($item->variant->variant_name); ?>)</span>
                                <?php endif; ?>
                            </p>

                            <form method="POST" action="<?php echo e(route('products.ratings.store', $product)); ?>" enctype="multipart/form-data" class="space-y-2 mt-2">
                                <?php echo csrf_field(); ?>
                                <div class="flex items-center gap-3">
                                    <label class="text-xs font-medium text-gray-700">Rating</label>
                                    <select name="rating" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php if(optional($existingRating)->rating === $i): echo 'selected'; endif; ?>><?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Comment (optional)</label>
                                    <textarea
                                        name="comment"
                                        rows="2"
                                        class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                    ><?php echo e(old('comment', optional($existingRating)->comment)); ?></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Photo (optional)</label>
                                    <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-900 hover:file:bg-blue-200">
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                                    <?php echo e($existingRating ? 'Update Rating' : 'Submit Rating'); ?>

                                </button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex justify-between items-center">
            <div class="flex gap-2">
                <a href="<?php echo e(route('orders.index')); ?>" class="inline-flex items-center gap-1 px-4 py-2 bg-white text-black border border-black text-sm font-medium rounded-full hover:bg-gray-100" style="border-radius: 0.375rem !important;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to My Orders
                </a>
                <?php if($order->status === 'pending'): ?>
                    <form method="POST" action="<?php echo e(route('orders.cancel', $order)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" data-confirm="Cancel this order? This cannot be undone." data-confirm-label="Cancel Order" class="inline-flex items-center px-4 py-2 bg-white text-black border border-black text-sm font-medium rounded-full hover:bg-gray-100" style="border-radius: 0.375rem !important;">
                            Cancel Order
                        </button>
                    </form>
                <?php endif; ?>
            </div>
            <a href="<?php echo e(route('orders.receipt', $order)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                Download Receipt PDF
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/orders/show.blade.php ENDPATH**/ ?>