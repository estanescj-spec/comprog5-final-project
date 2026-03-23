<?php $__env->startSection('header'); ?>
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <?php echo e($product->name); ?>

    </h2>
    <a href="<?php echo e(route('products.index')); ?>" class="text-sm text-blue-800 hover:text-blue-900">
        ← Back to Products
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
            <div class="grid md:grid-cols-2 gap-8 p-8">

                <!-- Left Column: Images -->
                <div>
                    <?php
                        $allImages = collect();
                        if ($product->images && $product->images->count()) {
                            foreach ($product->images->sortBy(fn($img) => [$img->is_primary ? 0 : 1, $img->order]) as $img) {
                                $allImages->push(asset('storage/' . $img->image_path));
                            }
                        }
                        if ($product->variants && $product->variants->count()) {
                            foreach ($product->variants as $variant) {
                                if ($variant->image) {
                                    $allImages->push(asset('storage/' . $variant->image));
                                }
                            }
                        }
                        $allImages = $allImages->unique()->values();
                    ?>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
                    <style>
                        .swiper-button-next, .swiper-button-prev {
                            color: #111 !important;
                            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.08));
                        }
                        .swiper-button-next:after, .swiper-button-prev:after {
                            font-size: 2.2rem;
                        }
                    </style>
                    <div class="swiper w-full aspect-square rounded-xl bg-gray-100 mb-3">
                        <div class="swiper-wrapper">
                            <?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="swiper-slide flex items-center justify-center">
                                    <img src="<?php echo e($img); ?>" alt="<?php echo e($product->name); ?>" class="object-contain w-full h-full cursor-pointer rounded-xl" onclick="openImageModal(this.src)">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                    <!-- Thumbnails row -->
                    <?php if($allImages->count() > 1): ?>
                    <div class="flex gap-2 mt-2 justify-center">
                        <?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img src="<?php echo e($img); ?>" alt="<?php echo e($product->name); ?> thumbnail" class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:border-blue-500 transition" onclick="window.swiperMain.slideToLoop(<?= $idx ?>)">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            window.swiperMain = new Swiper('.swiper', {
                                loop: true,
                                pagination: { el: '.swiper-pagination', clickable: true },
                                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                                slidesPerView: 1,
                                spaceBetween: 10,
                            });

                            // Variant click handler for both auth and guest
                            function showVariantImage(imageUrl) {
                                if (!imageUrl) return;
                                // Find the index of the image in the swiper
                                let idx = -1;
                                const slides = document.querySelectorAll('.swiper .swiper-slide img');
                                slides.forEach((img, i) => {
                                    if (img.src === imageUrl || img.getAttribute('src') === imageUrl) {
                                        idx = i;
                                    }
                                });
                                if (idx !== -1 && window.swiperMain) {
                                    window.swiperMain.slideToLoop(idx);
                                }
                            }

                            // For logged-in users
                            document.querySelectorAll('.variant-option').forEach(function(label) {
                                label.addEventListener('click', function(e) {
                                    const imgUrl = label.getAttribute('data-image');
                                    if (imgUrl) {
                                        showVariantImage(imgUrl);
                                    }
                                });
                            });
                            // For guests
                            document.querySelectorAll('.guest-variant').forEach(function(div) {
                                div.addEventListener('click', function(e) {
                                    const imgUrl = div.getAttribute('data-image');
                                    if (imgUrl) {
                                        showVariantImage(imgUrl);
                                    }
                                });
                            });
                        });
                    </script>
                </div>

                <!-- Right Column: Details -->
                <div class="flex flex-col">
                    <?php if($product->categories->count()): ?>
                        <div class="mb-4 flex flex-wrap gap-2">
                            <?php $__currentLoopData = $product->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('categories.products', $cat)); ?>"
                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-900 hover:bg-blue-200">
                                    <?php echo e($cat->name); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo e($product->name); ?></h1>

                    <div class="mb-6">
                        <span class="text-sm text-gray-500">From</span>
                        <span class="text-3xl font-bold text-gray-900">₱<?php echo e(number_format($product->variants->min('price') ?? 0, 2)); ?></span>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-600 leading-relaxed">
                            <?php echo e($product->description ?: 'No description available.'); ?>

                        </p>
                    </div>

                    <?php if($product->variants->count() > 0): ?>
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Available Variants</h3>
                            <?php if(auth()->guard()->check()): ?>
                            <form method="POST" action="<?php echo e(route('cart.add')); ?>" id="add-to-cart-form" class="space-y-4">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_variant_id" id="selected-variant-id">

                                <div class="space-y-2">
                                    <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center justify-between p-3 border-2 rounded-lg cursor-pointer hover:border-blue-300 transition-colors variant-option <?php echo e($variant->stock <= 0 ? 'opacity-50 cursor-not-allowed' : ''); ?>" data-variant-id="<?php echo e($variant->id); ?>" data-stock="<?php echo e($variant->stock); ?>" data-image="<?php echo e($variant->image ? asset('storage/' . $variant->image) : ''); ?>">
                                            <input type="radio" name="variant_selection" value="<?php echo e($variant->id); ?>" class="hidden" <?php echo e($variant->stock <= 0 ? 'disabled' : ''); ?> onchange="selectVariant(<?php echo e($variant->id); ?>)">
                                            <div class="flex items-center gap-3 flex-1" data-image="<?php echo e($variant->image ? asset('storage/' . $variant->image) : ''); ?>" onclick="onVariantImageClick(event, this)">
                                                <?php if($variant->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $variant->image)); ?>" alt="<?php echo e($variant->variant_name); ?>" class="h-12 w-12 object-cover rounded cursor-pointer border-2 border-gray-200 hover:border-blue-500 transition" data-image="<?php echo e(asset('storage/' . $variant->image)); ?>" onclick="changeMainImageFromImage(this); event.stopPropagation();">
                                                <?php else: ?>
                                                    <div class="h-12 w-12 bg-gray-100 rounded flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="flex gap-2 items-center text-sm font-medium"><?php echo e($variant->variant_name ?: 'Default Variant'); ?></div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <?php if($variant->stock > 0): ?>
                                                            <span class="text-emerald-600"><?php echo e($variant->stock); ?> in stock</span>
                                                        <?php else: ?>
                                                            <span class="text-blue-800">Out of stock</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-lg font-bold text-gray-900">₱<?php echo e(number_format($variant->price, 2)); ?></div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div class="flex items-center gap-3 pt-2">
                                    <div>
                                        <label for="quantity" class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                                        <div class="inline-flex items-center rounded-md border border-gray-300 overflow-hidden">
                                            <button type="button" id="qty-minus" class="px-3 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100">−</button>
                                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-16 border-0 focus:ring-0 text-sm text-center no-spinner">
                                            <button type="button" id="qty-plus" class="px-3 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100">+</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php else: ?>
                                <!-- Guest: show variant list read-only but clickable -->
                                <div class="space-y-2">
                                    <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div onclick="selectGuestVariant(this)"
                                            class="flex items-center justify-between p-3 border-2 rounded-lg transition-colors guest-variant <?php echo e($variant->stock <= 0 ? 'opacity-50' : 'cursor-pointer hover:border-blue-300'); ?>"
                                            data-image="<?php echo e($variant->image ? asset('storage/' . $variant->image) : ''); ?>">
                                            <div class="flex items-center gap-3">
                                                <?php if($variant->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $variant->image)); ?>" alt="<?php echo e($variant->variant_name); ?>" class="h-12 w-12 object-cover rounded border-2 border-gray-200 transition">
                                                <?php else: ?>
                                                    <div class="h-12 w-12 bg-gray-100 rounded flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <div class="text-sm font-medium"><?php echo e($variant->variant_name ?: 'Default Variant'); ?></div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <?php if($variant->stock > 0): ?>
                                                            <span class="text-emerald-600"><?php echo e($variant->stock); ?> in stock</span>
                                                        <?php else: ?>
                                                            <span class="text-blue-800">Out of stock</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-lg font-bold text-gray-900">₱<?php echo e(number_format($variant->price, 2)); ?></div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-auto pt-6 flex gap-3 items-center">
                        <?php if(auth()->guard()->check()): ?>
                            <?php $isFavorited = auth()->user()->favorites->where('product_id', $product->id)->count() > 0; ?>
                            <?php if($product->variants->count() > 0): ?>
                                <button type="submit" form="add-to-cart-form" id="add-to-cart-btn" disabled class="flex-1 px-6 py-3 bg-gray-300 text-gray-500 font-semibold rounded-full cursor-not-allowed">Select a Variant</button>
                                <button type="submit" form="add-to-cart-form" formaction="<?php echo e(route('orders.buyNow')); ?>" formmethod="POST" id="buy-now-btn" disabled class="flex-1 px-6 py-3 bg-gray-300 text-gray-500 font-semibold rounded-full cursor-not-allowed">Buy Now</button>
                                <form method="POST" action="<?php echo e($isFavorited ? route('favorites.destroy', $product) : route('favorites.store')); ?>" class="ml-2">
                                    <?php echo csrf_field(); ?>
                                    <?php if($isFavorited): ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-pink-600 hover:text-pink-800" title="Remove from favorites">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        </button>
                                    <?php else: ?>
                                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                        <button type="submit" class="text-gray-400 hover:text-pink-600" title="Add to favorites">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/></svg>
                                        </button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <p class="text-center text-gray-600">No variants available</p>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="flex-1 text-center px-6 py-3 bg-blue-700 text-white font-semibold rounded-full hover:bg-blue-800">Log in to Purchase</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Reviews -->
        <div class="mt-8 grid gap-6 md:grid-cols-3">
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Customer Reviews</h3>
                        <?php if($ratingsCount > 0): ?>
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold"><?php echo e(number_format($averageRating, 1)); ?>/5</span>
                                <span class="ml-1 text-xs text-gray-500">(<?php echo e($ratingsCount); ?> review<?php echo e($ratingsCount > 1 ? 's' : ''); ?>)</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if($ratingsCount === 0): ?>
                        <p class="text-sm text-gray-500">No reviews yet. Be the first to rate this product after your purchase.</p>
                    <?php else: ?>
                        <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                            <?php $__currentLoopData = $product->ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-100 rounded-lg p-3">
                                    <div class="flex items-start gap-3 mb-2">
                                        <?php if($rating->user->profile_photo_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $rating->user->profile_photo_path)); ?>" alt="<?php echo e($rating->user->name); ?>" class="h-10 w-10 rounded-full object-cover flex-shrink-0" style="border-radius: 50% !important;">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-600 flex-shrink-0" style="border-radius: 50% !important;">
                                                <?php echo e(substr($rating->user->name, 0, 1)); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-semibold text-gray-900"><?php echo e($rating->user->name); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo e($rating->created_at->format('M d, Y')); ?></p>
                                            </div>
                                            <?php if(!empty($reviewVariantByUser[$rating->user_id] ?? null)): ?>
                                                <p class="text-xs text-gray-500">Variant: <?php echo e($reviewVariantByUser[$rating->user_id]); ?></p>
                                            <?php endif; ?>
                                            <p class="text-xs text-amber-500">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php echo $i <= $rating->rating ? '★' : '☆'; ?>

                                                <?php endfor; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 review-comment"><?php echo e($rating->comment ?: 'No comment provided.'); ?></p>
                                    <?php if($rating->photo_path): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo e(asset('storage/' . $rating->photo_path)); ?>" alt="Review photo" class="h-28 w-28 object-cover rounded-lg border border-gray-200">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($product->categories->count()): ?>
            <div class="md:col-span-1 space-y-2">
                <?php if(auth()->guard()->check()): ?>
                    <?php if($userHasPurchased): ?>
                        <form method="POST" action="<?php echo e(route('products.ratings.store', $product)); ?>" enctype="multipart/form-data" class="bg-blue-50 rounded-xl border border-blue-100 p-4 space-y-3">
                            <?php echo csrf_field(); ?>
                            <h4 class="text-sm font-semibold text-gray-900">Leave a Review</h4>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Rating</label>
                                <div class="flex gap-1">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <input type="radio" name="rating" value="<?php echo e($i); ?>" id="rating-<?php echo e($i); ?>" class="hidden" required <?php if((int) old('rating', $existingUserRating?->rating) === $i): echo 'checked'; endif; ?>>
                                        <label for="rating-<?php echo e($i); ?>" class="text-2xl cursor-pointer text-gray-300 hover:text-amber-400 transition" data-value="<?php echo e($i); ?>">☆</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Comment (optional)</label>
                                <textarea name="comment" maxlength="2000" rows="3" placeholder="Share your thoughts..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500"><?php echo e(old('comment', $existingUserRating?->comment)); ?></textarea>
                                <p class="text-xs text-gray-500 mt-1"><span id="char-count"><?php echo e(strlen(old('comment', $existingUserRating?->comment ?? ''))); ?></span>/2000</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Photo (optional)</label>
                                <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-900 hover:file:bg-blue-200">
                                <?php if($existingUserRating?->photo_path): ?>
                                    <p class="text-xs text-gray-500 mt-2 mb-1">Current uploaded photo</p>
                                    <div class="instant-image-preview-wrap relative mt-1 w-fit">
                                        <img src="<?php echo e(asset('storage/' . $existingUserRating->photo_path)); ?>" alt="Current review photo" class="instant-image-preview h-24 w-24 rounded-lg border border-gray-200 object-cover">
                                        <button type="button" class="instant-image-remove absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-600 text-white text-xs font-bold hover:bg-red-700" style="border-radius: 50% !important;" aria-label="Remove selected image">×</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                <?php echo e($existingUserRating ? 'Update Review' : 'Submit Review'); ?>

                            </button>
                        </form>
                        <script>
                            function syncSelectedStars() {
                                const selected = document.querySelector('input[name="rating"]:checked');
                                const selectedValue = selected ? parseInt(selected.value, 10) : 0;

                                document.querySelectorAll('label[for^="rating-"]').forEach((l, i) => {
                                    l.textContent = (i + 1) <= selectedValue ? '★' : '☆';
                                    l.classList.toggle('text-amber-400', (i + 1) <= selectedValue);
                                    l.classList.toggle('text-gray-300', (i + 1) > selectedValue);
                                });
                            }

                            document.querySelectorAll('input[name="rating"]').forEach(radio => {
                                radio.addEventListener('change', syncSelectedStars);
                            });

                            syncSelectedStars();

                            document.querySelector('textarea[name="comment"]')?.addEventListener('input', function() {
                                document.getElementById('char-count').textContent = this.value.length;
                            });
                        </script>
                    <?php else: ?>
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 text-center">
                            <p class="text-sm text-gray-600">Purchase this product to leave a review.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center mt-2 mb-4">
                        <p class="text-sm text-gray-600 mb-2">Sign in to leave a review.</p>
                        <a href="<?php echo e(route('login')); ?>" class="inline-block px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded-full shadow hover:bg-blue-700 transition">Sign In</a>
                    </div>
                <?php endif; ?>

                <?php $__currentLoopData = $product->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('categories.products', $cat)); ?>" class="block w-full text-center px-4 py-3 bg-blue-50 text-blue-900 text-sm font-medium rounded-2xl hover:bg-blue-100 border border-blue-100">
                        View more in <?php echo e($cat->name); ?> →
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-screen">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Product Image" class="max-w-full max-h-screen object-contain" onclick="event.stopPropagation()">
    </div>
</div>

<script>
const originalMainImage = document.getElementById('main-image') ? document.getElementById('main-image').src : null;
const quantityInput = document.getElementById('quantity');

function clampQuantity() {
    if (!quantityInput) return;
    const min = parseInt(quantityInput.min || '1', 10);
    const max = parseInt(quantityInput.max || '9999', 10);
    let value = parseInt(quantityInput.value || String(min), 10);

    if (Number.isNaN(value)) value = min;
    if (value < min) value = min;
    if (value > max) value = max;

    quantityInput.value = value;
}

function adjustQuantity(delta) {
    if (!quantityInput) return;
    const current = parseInt(quantityInput.value || '1', 10) || 1;
    quantityInput.value = current + delta;
    clampQuantity();
}

document.getElementById('qty-minus')?.addEventListener('click', function () {
    adjustQuantity(-1);
});

document.getElementById('qty-plus')?.addEventListener('click', function () {
    adjustQuantity(1);
});

quantityInput?.addEventListener('input', clampQuantity);

function setMainImage(src) {
    const mainImg = document.getElementById('main-image');
    const placeholder = document.getElementById('main-image-placeholder');
    if (!mainImg) return;
    if (src) {
        mainImg.src = src;
        mainImg.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    } else {
        mainImg.classList.add('hidden');
        if (placeholder) placeholder.classList.remove('hidden');
    }
}

function changeMainImage(imageSrc, element){
    setMainImage(imageSrc);
    document.querySelectorAll('.product-thumbnail').forEach(div => {
        div.classList.remove('border-blue-500');
        div.classList.add('border-transparent');
    });

    if(element){
        element.classList.remove('border-transparent');
        element.classList.add('border-blue-500');
    }
}

function changeMainImageFromData(element){
    changeMainImage(element.dataset.image || '', element);
}

function changeMainImageFromImage(imgEl){
    changeMainImage(imgEl.dataset.image || imgEl.src || '', null);
}

function onVariantImageClick(event, wrapper){
    if (event.target.tagName !== 'IMG') return;
    setMainImage(wrapper.dataset.image || '');
}

function openImageModal(src){document.getElementById('modalImage').src=src;document.getElementById('imageModal').classList.remove('hidden');document.getElementById('imageModal').classList.add('flex');}
function closeImageModal(){document.getElementById('imageModal').classList.add('hidden');document.getElementById('imageModal').classList.remove('flex');}
function selectVariant(id){
    document.getElementById('selected-variant-id').value=id;
    let btn=document.getElementById('add-to-cart-btn');btn.disabled=false;btn.classList.remove('bg-gray-300','text-gray-500','cursor-not-allowed');btn.classList.add('bg-blue-500','text-white','hover:bg-blue-800');btn.textContent='Add to Cart';
    let buyBtn=document.getElementById('buy-now-btn');buyBtn.disabled=false;buyBtn.classList.remove('bg-gray-300','text-gray-500','cursor-not-allowed');buyBtn.classList.add('bg-black','text-white','hover:bg-gray-800');
    document.querySelectorAll('.variant-option').forEach(opt=>{opt.classList.remove('border-blue-500','bg-blue-50');opt.classList.add('border-gray-200');});
    let selected=document.querySelector(`[data-variant-id="${id}"]`);
    if(selected){
        const stock = parseInt(selected.dataset.stock || '1', 10);
        if (quantityInput) {
            quantityInput.max = String(Math.max(stock, 1));
            clampQuantity();
        }

        selected.classList.remove('border-gray-200');
        selected.classList.add('border-blue-500','bg-blue-50');
        const variantImage = selected.dataset.image;
        setMainImage(variantImage || originalMainImage || '');

        if (!variantImage) {
            const firstThumb = document.querySelector('.product-thumbnail');
            if (firstThumb) {
                firstThumb.classList.remove('border-transparent');
                firstThumb.classList.add('border-blue-500');
            }
        }
    }
}
function selectGuestVariant(el) {
    document.querySelectorAll('.guest-variant').forEach(v => {
        v.classList.remove('border-blue-500', 'bg-blue-50');
        v.classList.add('border-gray-200');
    });
    el.classList.remove('border-gray-200');
    el.classList.add('border-blue-500', 'bg-blue-50');
    const img = el.dataset.image;
    if (img) setMainImage(img);
}

const FOUL_WORDS = [
    'fuck', 'shit', 'bitch', 'gago', 'putangina', 'tanginamo', 'tanga',
];

function filterProfanity(text) {
    let filtered = text;
    FOUL_WORDS.forEach(word => {
        const regex = new RegExp(`\\b${word}\\b`, 'gi');
        filtered = filtered.replace(regex, '*'.repeat(word.length));
    });
    return filtered;
}

document.addEventListener('DOMContentLoaded', function () {
    clampQuantity();

    document.querySelectorAll('.review-comment').forEach(el => {
        el.textContent = filterProfanity(el.textContent);
    });
});
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\skincare\resources\views/products/show.blade.php ENDPATH**/ ?>