<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-lg text-gray-800 leading-tight text-center uppercase tracking-wider mb-2">
        EXPLORE OUR PRODUCT FAMILIES
    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-4 pb-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <aside class="lg:col-span-3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sticky top-24">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Browse Categories</h3>

                    <div class="space-y-1">
                        <a href="<?php echo e(route('categories.index', array_filter(['search' => $filters['search'] ?? request('search')]))); ?>"
                           class="block px-3 py-2 rounded-lg text-sm <?php echo e(empty($selectedCategory) ? 'bg-blue-50 text-blue-800 font-medium' : 'text-gray-700 hover:bg-gray-50'); ?>">
                            All Categories
                        </a>

                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('categories.index', array_filter(['category' => $category->id, 'search' => $filters['search'] ?? request('search')]))); ?>"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm <?php echo e(($selectedCategory?->id === $category->id) ? 'bg-blue-50 text-blue-800 font-medium' : 'text-gray-700 hover:bg-gray-50'); ?>">
                                <span><?php echo e($category->name); ?></span>
                                <span class="text-xs text-gray-500"><?php echo e($category->products_count); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </aside>

            <section class="lg:col-span-9">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
                    <form method="GET" action="<?php echo e(route('categories.index')); ?>" class="flex flex-col sm:flex-row gap-3" data-live-search-form>
                        <?php if(!empty($selectedCategory)): ?>
                            <input type="hidden" name="category" value="<?php echo e($selectedCategory->id); ?>">
                        <?php endif; ?>
                        <input type="text" name="search" value="<?php echo e($filters['search'] ?? request('search')); ?>" placeholder="Search products in categories..." data-live-search
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </form>

                    <?php if($selectedCategory): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                            <h3 class="font-semibold text-gray-900 text-sm mb-1"><?php echo e($selectedCategory->name); ?></h3>
                            <p class="text-sm text-gray-600"><?php echo e($selectedCategory->description); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="mt-3 text-xs text-gray-500">
                        <?php if($selectedCategory): ?>
                            Showing <span class="font-normal text-gray-600"><?php echo e($selectedCategory->name); ?></span> products
                        <?php else: ?>
                            Showing products from all categories
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($products->count() > 0): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
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

                                    <h3 class="text-lg font-semibold text-gray-900 mb-1"><?php echo e($product->name); ?></h3>

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

                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2"><?php echo e($product->description); ?></p>

                                    <div>
                                        <span class="text-xs text-gray-500">From</span>
                                        <span class="text-xl font-bold text-gray-900">₱<?php echo e(number_format($product->variants->min('price') ?? 0, 2)); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-8">
                        <?php echo e($products->withQueryString()->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-600 mb-4">
                            <?php if(!empty($filters['search'] ?? request('search'))): ?>
                                No products match your search.
                            <?php elseif($selectedCategory): ?>
                                No products in <?php echo e($selectedCategory->name); ?> yet.
                            <?php else: ?>
                                No products available at the moment.
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\skincare\resources\views/categories/index.blade.php ENDPATH**/ ?>