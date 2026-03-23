<?php $__env->startSection('header'); ?>
    <div class="text-center mb-2">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight mb-2">Face and Body Skincare</h2>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="pt-6 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <form method="GET" action="<?php echo e(route('products.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3 items-end" data-live-search-form>
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-xs font-medium text-gray-600 mb-1">Search product/service</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="<?php echo e($filters['search'] ?? request('search')); ?>"
                            placeholder="I'm looking for..."
                            data-live-search
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-medium text-gray-600 mb-1">Category / Brand / Type</label>
                        <?php
                            $selectedCategoryId = $filters['category']
                                ?? (isset($category) ? $category->id : request()->query('category'));
                        ?>
                        <select
                            id="category"
                            name="category"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">All</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->id); ?>" <?php if((string) $selectedCategoryId === (string) $cat->id): echo 'selected'; endif; ?>>
                                    <?php echo e($cat->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="min_price" class="block text-xs font-medium text-gray-600 mb-1">Min Price</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="min_price"
                            name="min_price"
                            value="<?php echo e($filters['min_price'] ?? request('min_price')); ?>"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <label for="max_price" class="block text-xs font-medium text-gray-600 mb-1">Max Price</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="max_price"
                            name="max_price"
                            value="<?php echo e($filters['max_price'] ?? request('max_price')); ?>"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800">
                            Apply
                        </button>
                    </div>

                    <div>
                        <a href="<?php echo e(route('products.index')); ?>" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-full hover:bg-gray-200">
                            Clear
                        </a>
                    </div>
                </form>

                <?php if(!empty($filters['search'] ?? request('search')) || !empty($filters['category'] ?? request('category')) || isset($filters['min_price']) || isset($filters['max_price']) || request()->filled('min_price') || request()->filled('max_price')): ?>
                    <p class="mt-2 text-xs text-gray-500">Filtered results shown</p>
                <?php endif; ?>

                <?php $__errorArgs = ['max_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-sm text-blue-800"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <?php if($products->count() > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow cursor-pointer"
                             data-href="<?php echo e(route('products.show', $product)); ?>"
                             role="link"
                             tabindex="0"
                             onclick="window.location.href=this.dataset.href"
                             onkeydown="if(event.key==='Enter' || event.key===' '){ event.preventDefault(); window.location.href=this.dataset.href; }">
                                <?php
                                    $cardImage = ($product->images && $product->images->isNotEmpty())
                                        ? asset('storage/' . $product->images->first()->image_path)
                                        : ($product->variants->firstWhere('image', '!=', null)?->image
                                            ? asset('storage/' . $product->variants->firstWhere('image', '!=', null)->image)
                                            : null);
                                ?>
                                <?php if($cardImage): ?>
                                    <div class="aspect-square overflow-hidden bg-gray-100">
                                        <img src="<?php echo e($cardImage); ?>" alt="<?php echo e($product->name); ?>"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                <?php else: ?>
                                    <div class="aspect-square bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>

                            <div class="p-4">
                                <div class="mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-900">
                                        <?php echo e($product->categories->pluck('name')->join(', ')); ?>

                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    <?php echo e($product->name); ?>

                                </h3>

                                <div class="mb-2 flex items-center gap-2">
                                    <?php if(!is_null($product->ratings_avg_rating)): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                            ★ <?php echo e(number_format((float) $product->ratings_avg_rating, 1)); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                            No ratings
                                        </span>
                                    <?php endif; ?>

                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                        <?php echo e((int) ($product->units_sold ?? 0)); ?> sold
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    <?php echo e($product->description); ?>

                                </p>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-gray-500">From</span>
                                        <span class="text-xl font-bold text-gray-900">₱<?php echo e(number_format($product->variants->min('price') ?? 0, 2)); ?></span>
                                    </div>
                                </div>

                                <?php if($product->variants->count() > 0): ?>
                                    <div class="mt-2 text-xs text-gray-500">
                                        <?php echo e($product->variants->count()); ?> variant(s) available
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-8">
                    <?php echo e($products->withQueryString()->links()); ?>

                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No products found</h3>
                    <p class="text-gray-600 mb-4">
                        <?php if(request('search')): ?>
                            We couldn't find any products matching "<?php echo e(request('search')); ?>".
                        <?php elseif(isset($category)): ?>
                            No products in the <?php echo e($category->name ?? 'Unknown'); ?> category yet.
                        <?php else: ?>
                            No products available at the moment.
                        <?php endif; ?>
                    </p>
                    <?php if(request('search')): ?>
                        <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800">
                            View All Products
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/products/index.blade.php ENDPATH**/ ?>