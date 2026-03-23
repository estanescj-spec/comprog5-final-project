

<?php $__env->startSection('header'); ?>
    <div class="text-center mb-2">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight mb-2">My Favorites</h2>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="pt-6 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <?php if($favorites->count() > 0): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php $__currentLoopData = $favorites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $favorite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $product = $favorite->product; ?>
                            <?php if($product): ?>
                                  <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow cursor-pointer"
                                      data-href="<?php echo e(route('products.show', $product)); ?>"
                                      role="link"
                                      tabindex="0"
                                      onclick="if(event.target.closest('form')) return; window.location.href=this.dataset.href"
                                      onkeydown="if((event.key==='Enter' || event.key===' ') && !event.target.closest('form')){ event.preventDefault(); window.location.href=this.dataset.href; }">
                                    <div class="aspect-square overflow-hidden bg-gray-100">
                                        <img src="<?php echo e($product->images->first()?->image_path ? asset('storage/' . $product->images->first()->image_path) : 'https://via.placeholder.com/300x300?text=No+Image'); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1"><?php echo e($product->name); ?></h3>
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2"><?php echo e($product->description); ?></p>
                                        <form method="POST" action="<?php echo e(route('favorites.destroy', $product)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs font-medium" data-confirm="Remove from favorites?" data-confirm-label="Remove">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No favorites yet</h3>
                        <p class="text-gray-600">Browse products and add them to your favorites.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\skincare\resources\views/favorites/index.blade.php ENDPATH**/ ?>