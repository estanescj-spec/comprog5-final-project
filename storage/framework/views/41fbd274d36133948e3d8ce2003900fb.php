<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <?php echo e(__('Checkout')); ?>

    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <h3 class="text-2xl font-bold mb-6">Complete Your Order</h3>

            <form method="POST" action="<?php echo e(route('orders.store')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Shipping Address
                    </label>
                    <textarea name="shipping_address" id="shipping_address" rows="3" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('shipping_address', auth()->user()->address ?? '')); ?></textarea>
                    <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-blue-800"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Method
                    </label>
                    <select name="payment_method" id="payment_method"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="COD" <?php if(old('payment_method', 'COD') === 'COD'): echo 'selected'; endif; ?>>Cash on Delivery (COD)</option>
                        <option value="GCash" <?php if(old('payment_method') === 'GCash'): echo 'selected'; endif; ?>>GCash</option>
                        <option value="Maya" <?php if(old('payment_method') === 'Maya'): echo 'selected'; endif; ?>>Maya</option>
                        <option value="card" <?php if(old('payment_method') === 'card'): echo 'selected'; endif; ?>>Credit/Debit Card</option>
                        <option value="BankTransfer" <?php if(old('payment_method') === 'BankTransfer'): echo 'selected'; endif; ?>>Bank Transfer</option>
                    </select>
                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-blue-800"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="border-t pt-6">
                    <h4 class="font-semibold mb-4">Order Summary</h4>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between text-sm">
                                <span><?php echo e($item->variant->product->name); ?> <?php echo e($item->variant->variant_name ? '(' . $item->variant->variant_name . ')' : ''); ?> x<?php echo e($item->quantity); ?></span>
                                <span>₱<?php echo e(number_format($item->variant->price * $item->quantity, 2)); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="border-t mt-4 pt-4 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-blue-800">₱<?php echo e(number_format($total, 2)); ?></span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-800 transition-colors">
                        Place Order
                    </button>
                    <a href="<?php echo e(route('cart.index')); ?>" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Back to Cart
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\skincare\resources\views/orders/create.blade.php ENDPATH**/ ?>