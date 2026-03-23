<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        My Cart
    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <?php if($cartItems->isEmpty()): ?>
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-600 mb-4">Add some products to your cart to get started.</p>
                     <a href="<?php echo e(route('products.index')); ?>"
                         class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 9999px !important;">
                    Browse Products
                </a>
            </div>
        <?php else: ?>

        <div class="grid md:grid-cols-3 gap-6">

            
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden p-6">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">
                            Cart Items (<?php echo e($cartItems->count()); ?>)
                        </h3>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" id="selectAll">
                            Select All
                        </label>
                    </div>

                    <div class="space-y-4">

                        <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex gap-4 p-4 border border-gray-200 rounded-lg cart-item">

                                
                                <input type="checkbox"
                                       value="<?php echo e($item->id); ?>"
                                       class="itemCheckbox mt-2">

                                
                                <?php if($item->variant->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $item->variant->image)); ?>"
                                         class="h-20 w-20 object-cover rounded">
                                <?php else: ?>
                                    <div class="h-20 w-20 bg-gray-100 rounded"></div>
                                <?php endif; ?>

                                
                                <div class="flex-1">
                                    <h4 class="font-semibold">
                                        <?php echo e($item->variant->product->name); ?>

                                    </h4>

                                    <p class="text-sm text-gray-600">
                                        Variant:
                                        <span class="font-medium">
                                            <?php echo e($item->variant->variant_name ?: 'Default Variant'); ?>

                                        </span>
                                    </p>

                                    <p class="text-xs text-gray-500 item-stock">
                                        Stock: <?php echo e($item->variant->stock); ?>

                                    </p>

                                    <p class="text-blue-800 font-semibold mt-1 item-price">
                                        ₱<?php echo e(number_format($item->variant->price, 2)); ?>

                                    </p>

                                    
                                    <form method="POST"
                                          action="<?php echo e(route('cart.update', $item)); ?>"
                                          class="mt-3 flex items-center gap-2">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>

                                        <select name="product_variant_id"
                                            class="variant-select pl-2 pr-8 py-2 border border-gray-300 text-sm bg-white"
                                            style="border-radius: 0 !important;"
                                                data-target="cart-qty-<?php echo e($item->id); ?>">
                                            <?php $__currentLoopData = $item->variant->product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variantOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($variantOption->id); ?>"
                                                        data-price="<?php echo e($variantOption->price); ?>"
                                                        data-stock="<?php echo e($variantOption->stock); ?>"
                                                        <?php if($variantOption->id === $item->variant->id): echo 'selected'; endif; ?>
                                                        <?php if($variantOption->stock < 1 && $variantOption->id !== $item->variant->id): echo 'disabled'; endif; ?>>
                                                    <?php echo e($variantOption->variant_name ?: 'Default Variant'); ?>

                                                    <?php if($variantOption->stock < 1 && $variantOption->id !== $item->variant->id): ?>
                                                        - Out of stock
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                        <div class="inline-flex items-center rounded border border-gray-300 overflow-hidden">
                                            <button type="button"
                                                    class="px-3 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 cart-qty-btn"
                                                    data-target="cart-qty-<?php echo e($item->id); ?>"
                                                    data-action="minus">−</button>

                                            <input type="number"
                                                   id="cart-qty-<?php echo e($item->id); ?>"
                                                   name="quantity"
                                                   value="<?php echo e($item->quantity); ?>"
                                                   min="1"
                                                   max="<?php echo e($item->variant->stock); ?>"
                                                   class="w-14 border-0 text-center text-sm item-quantity no-spinner focus:ring-0">

                                            <button type="button"
                                                    class="px-3 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 cart-qty-btn"
                                                    data-target="cart-qty-<?php echo e($item->id); ?>"
                                                    data-action="plus">+</button>
                                        </div>

                                        <button type="submit"
                                                class="px-3 py-1 bg-blue-500 text-white text-xs rounded-full hover:bg-blue-600">
                                            Update
                                        </button>
                                    </form>
                                </div>

                                <form method="POST"
                                      action="<?php echo e(route('cart.remove', $item)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button type="submit"
                                            data-confirm="Remove '<?php echo e($item->variant->product->name); ?>' from your cart?"
                                            data-confirm-label="Remove"
                                            class="px-3 py-2 bg-blue-50 text-blue-900 rounded-full hover:bg-blue-100">
                                        Remove
                                    </button>
                                </form>

                                
                                <input type="hidden"
                                       name="selected_items[]"
                                       value="<?php echo e($item->id); ?>"
                                       class="selected-hidden"
                                       form="checkoutForm"
                                       disabled>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>
            </div>

            
            <div>
                <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-6">

                    <h3 class="text-lg font-semibold mb-4">Order Summary</h3>

                    <div class="flex justify-between text-sm mb-4">
                        <span>Total (selected items)</span>
                        <span id="selectedTotal">₱0.00</span>
                    </div>

                    
                    <form method="GET"
                          action="<?php echo e(route('orders.create')); ?>"
                          id="checkoutForm">

                        <button type="submit"
                                class="w-full px-6 py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-800 transition-colors mb-3">
                            Proceed to Checkout
                        </button>
                    </form>

                    <a href="<?php echo e(route('products.index')); ?>"
                       class="w-full block text-center px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-300 transition-colors">
                        Continue Shopping
                    </a>

                </div>
            </div>

        </div>

        <?php endif; ?>

    </div>
</div>


<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.itemCheckbox');
    const hiddenInputs = document.querySelectorAll('.selected-hidden');
    const totalEl = document.getElementById('selectedTotal');

    function syncHiddenInputs() {
        checkboxes.forEach((cb, index) => {
            hiddenInputs[index].disabled = !cb.checked;
        });
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        checkboxes.forEach((cb, index) => {
            if (cb.checked) {
                const itemDiv = cb.closest('.cart-item');
                const price = parseFloat(itemDiv.querySelector('.item-price').textContent.replace('₱','').replace(/,/g,''));
                const quantity = parseInt(itemDiv.querySelector('.item-quantity').value);
                total += price * quantity;
            }
        });
        totalEl.textContent = '₱' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    selectAll?.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        syncHiddenInputs();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', syncHiddenInputs);
    });

    document.querySelectorAll('.item-quantity').forEach(qty => {
        qty.addEventListener('input', updateTotal);
    });

    function formatPeso(value) {
        return '₱' + Number(value).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    document.querySelectorAll('.variant-select').forEach(select => {
        select.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price || '0');
            const stock = parseInt(selectedOption.dataset.stock || '0', 10);

            const itemDiv = this.closest('.cart-item');
            if (!itemDiv) return;

            const priceEl = itemDiv.querySelector('.item-price');
            const stockEl = itemDiv.querySelector('.item-stock');
            const qtyInput = document.getElementById(this.dataset.target);

            if (priceEl) {
                priceEl.textContent = formatPeso(price);
            }

            if (stockEl) {
                stockEl.textContent = `Stock: ${stock}`;
            }

            if (qtyInput) {
                qtyInput.max = stock;
                if (parseInt(qtyInput.value || '1', 10) > stock) {
                    qtyInput.value = stock > 0 ? stock : 1;
                }
            }

            updateTotal();
        });
    });

    document.querySelectorAll('.cart-qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const action = this.dataset.action;
            const input = document.getElementById(targetId);
            if (!input) return;

            const min = parseInt(input.min || '1', 10);
            const max = parseInt(input.max || '9999', 10);
            let value = parseInt(input.value || String(min), 10);

            if (Number.isNaN(value)) value = min;
            value = action === 'plus' ? value + 1 : value - 1;
            value = Math.max(min, Math.min(max, value));

            input.value = value;
            updateTotal();
        });
    });

    // initialize
    updateTotal();
</script>

<style>
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .no-spinner {
        appearance: textfield;
        -moz-appearance: textfield;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\skincare\resources\views/cart/index.blade.php ENDPATH**/ ?>