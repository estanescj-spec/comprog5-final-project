<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-lg text-gray-800 leading-tight text-center uppercase tracking-wider mb-2">
        EXPLORE OUR COLLECTION
    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="pt-4 pb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <section class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-blue-100 w-screen max-w-none relative left-1/2 -translate-x-1/2">
                <?php
                    $coverImage = $featuredImagePath ? asset('storage/' . $featuredImagePath) : null;
                ?>

                <div class="relative">
                    <?php if($coverImage): ?>
                        <img src="<?php echo e($coverImage); ?>" alt="Featured" class="block w-full h-auto">
                    <?php else: ?>
                        <div class="h-64 sm:h-80 lg:h-96 bg-gradient-to-r from-blue-100 via-blue-50 to-white"></div>
                    <?php endif; ?>
                    <div class="absolute inset-0 grid grid-cols-1 lg:grid-cols-2">
                        <div class="hidden lg:block"></div>
                        <div class="flex items-center justify-end p-6 sm:p-8">
                            <div class="text-black max-w-xl text-right mr-8 sm:mr-12 lg:mr-20" style="text-align: left !important;">
                                <h1 class="text-2xl sm:text-3xl font-bold mb-2 text-black" style="text-align: left !important;"><?php echo e($bannerTitle); ?></h1>
                                <p class="text-sm sm:text-base text-black/80" style="text-align: left !important;"><?php echo e($bannerSubtitle); ?></p>
                                <?php if(auth()->guard()->check()): ?>
                                    <a href="<?php echo e(route('products.index')); ?>" class="inline-flex mt-4 items-center bg-blue-500 text-white font-medium rounded-full hover:bg-blue-500" style="padding: 1.3rem 2.1rem !important; font-size: 10px !important; line-height: 1 !important;">SHOP NOW</a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('login')); ?>" class="inline-flex mt-4 items-center bg-blue-500 text-white font-medium rounded-full hover:bg-blue-500" style="padding: 1.4rem 2.1rem !important; font-size: 10px !important; line-height: 1 !important;">SHOP NOW</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->role === 'admin'): ?>
                        <div class="border-t border-blue-100 p-4 sm:p-6 bg-blue-50/40">
                            <form method="POST" action="<?php echo e(route('admin.home.featured-image.update')); ?>" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="file" name="featured_image" accept="image/*" required class="block w-full sm:w-auto text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-900 hover:file:bg-blue-200">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-xs font-medium rounded-full hover:bg-blue-800">Update Featured Cover</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>

            <section class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-blue-100 p-6">
                <div class="mb-4 flex flex-col items-center justify-center">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 uppercase tracking-wide text-center">DISCOVER OUR NEW RELEASES</h3>
                </div>
                <div class="pb-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <?php $__currentLoopData = $newReleases->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $cardImage = ($product->images && $product->images->isNotEmpty())
                                ? asset('storage/' . $product->images->first()->image_path)
                                : ($product->variants->firstWhere('image', '!=', null)?->image
                                    ? asset('storage/' . $product->variants->firstWhere('image', '!=', null)->image)
                                    : null);
                        ?>
                            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow cursor-pointer w-[260px] sm:w-[280px] md:w-[300px] shrink-0 snap-start"
                             data-href="<?php echo e(route('products.show', $product)); ?>"
                             role="link"
                             tabindex="0"
                             onclick="window.location.href=this.dataset.href"
                             onkeydown="if(event.key==='Enter' || event.key===' '){ event.preventDefault(); window.location.href=this.dataset.href; }">
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

                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    <?php echo e($product->description); ?>

                                </p>

                                <div>
                                    <span class="text-xs text-gray-500">From</span>
                                    <span class="text-xl font-bold text-gray-900">₱<?php echo e(number_format($product->variants->min('price') ?? 0, 2)); ?></span>
                                </div>

                                <?php if($product->variants->count() > 0): ?>
                                    <div class="mt-2 text-xs text-gray-500">
                                        <?php echo e($product->variants->count()); ?> variant(s) available
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($newReleases->isEmpty()): ?>
                        <p class="text-sm text-gray-500">No products available yet.</p>
                    <?php endif; ?>
                    </div>
                    <div class="flex justify-end mt-2">
                        <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center gap-1 text-xs text-blue-700 hover:text-blue-800">
                            View all products
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </section>

            <section class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-blue-100 p-6">
                <div class="mb-4 flex flex-col items-center justify-center">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 uppercase tracking-wide text-center">WHAT'S TRENDING</h3>
                </div>
                <div class="pb-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <?php $__currentLoopData = $bestSellers->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $cardImage = ($product->images && $product->images->isNotEmpty())
                                ? asset('storage/' . $product->images->first()->image_path)
                                : ($product->variants->firstWhere('image', '!=', null)?->image
                                    ? asset('storage/' . $product->variants->firstWhere('image', '!=', null)->image)
                                    : null);
                        ?>
                            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow cursor-pointer w-[260px] sm:w-[280px] md:w-[300px] shrink-0 snap-start"
                             data-href="<?php echo e(route('products.show', $product)); ?>"
                             role="link"
                             tabindex="0"
                             onclick="window.location.href=this.dataset.href"
                             onkeydown="if(event.key==='Enter' || event.key===' '){ event.preventDefault(); window.location.href=this.dataset.href; }">
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

                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    <?php echo e($product->description); ?>

                                </p>

                                <div>
                                    <span class="text-xs text-gray-500">From</span>
                                    <span class="text-xl font-bold text-gray-900">₱<?php echo e(number_format($product->variants->min('price') ?? 0, 2)); ?></span>
                                </div>

                                <?php if($product->variants->count() > 0): ?>
                                    <div class="mt-2 text-xs text-gray-500">
                                        <?php echo e($product->variants->count()); ?> variant(s) available
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($bestSellers->isEmpty()): ?>
                        <p class="text-sm text-gray-500">No sales data available yet.</p>
                    <?php endif; ?>
                    </div>
                    <div class="flex justify-end mt-2">
                        <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center gap-1 text-xs text-blue-700 hover:text-blue-800">
                            View all products
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/dashboard.blade.php ENDPATH**/ ?>