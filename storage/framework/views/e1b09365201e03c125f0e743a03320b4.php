<?php $__env->startSection('header'); ?>
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    <?php echo e(__('Add New Product')); ?>

</h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
            <div class="p-6 text-gray-900">
                <form method="POST" action="<?php echo e(route('admin.products.store')); ?>" enctype="multipart/form-data" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="border-radius: 0.375rem !important;">
                        <?php $__errorArgs = ['name'];
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

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-2 p-2 border rounded-lg cursor-pointer hover:bg-blue-50" style="border-radius: 0.375rem !important;">
                                    <input type="checkbox" name="categories[]" value="<?php echo e($category->id); ?>"
                                        <?php echo e(in_array($category->id, old('categories', [])) ? 'checked' : ''); ?>

                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" style="border-radius: 0.375rem !important;">
                                    <span class="text-sm text-gray-700"><?php echo e($category->name); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['categories'];
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

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="border-radius: 0.375rem !important;"><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
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

                    <!-- Main Image (Single) -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Main Product Image (Single)</label>
                        <input type="file" name="image" id="image" accept="image/*" onchange="previewMainImage(event)"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100" style="border-radius: 0.375rem !important;">
                        <p class="mt-1 text-xs text-gray-500">Optional. This is the default image when gallery images are not available.</p>
                        <img id="main-image-preview" src="" alt="Main image preview" class="hidden mt-3 h-24 w-24 object-cover rounded-lg border border-gray-200">
                        <?php $__errorArgs = ['image'];
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

                    <!-- Images -->
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Product Images</label>
                        <input type="file" name="images[]" id="images" accept="image/*" multiple onchange="previewImages(event)"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">You can select multiple images. PNG, JPG, GIF up to 2MB each</p>
                        <div id="image-preview" class="mt-3 grid grid-cols-4 gap-2"></div>
                        <?php $__errorArgs = ['images.*'];
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

                    <!-- Product Variants -->
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Product Variants <span class="text-blue-500 text-sm font-normal">(At least one required)</span></h3>
                        <div id="variants-container" class="space-y-4">
                            <div class="variant-item grid grid-cols-1 md:grid-cols-4 gap-3 p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Variant Name</label>
                                    <input type="text" name="variants[0][variant_name]" placeholder="e.g. 50ml Rose"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Price (₱)</label>
                                    <input type="number" name="variants[0][price]" step="0.01" min="0"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Stock</label>
                                    <input type="number" name="variants[0][stock]" min="0"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Variant Image</label>
                                    <input type="file" name="variants[0][image]" accept="image/*"
                                        class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100"
                                        onchange="previewVariantImage(event, 'vpreview-0')">
                                    <img id="vpreview-0" src="" alt="Preview" class="hidden mt-1 h-14 w-14 object-cover rounded-lg border border-gray-200">
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="addVariant()" class="mt-3 inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-full hover:bg-blue-600" style="border-radius: 0.375rem !important;">
                            + Add Another Variant
                        </button>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                            Create Product
                        </button>
                        <a href="<?php echo e(route('admin.products.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-full hover:bg-gray-300" style="border-radius: 0.375rem !important;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let variantCount = 1;

function previewImages(event) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    const files = event.target.files;
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                <span class="absolute top-1 right-1 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded">${i + 1}</span>
            `;
            preview.appendChild(div);
        };
        
        reader.readAsDataURL(file);
    }
}

function previewMainImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const preview = document.getElementById('main-image-preview');
    if (!preview) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function previewVariantImage(event, previewId) {
    const file = event.target.files[0];
    if (!file) return;
    const img = document.getElementById(previewId);
    if (!img) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        img.src = e.target.result;
        img.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function addVariant() {
    const container = document.getElementById('variants-container');
    const newVariant = document.createElement('div');
    newVariant.className = 'variant-item grid grid-cols-1 md:grid-cols-4 gap-3 p-4 bg-gray-50 rounded-lg relative';
    newVariant.innerHTML = `
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Variant Name</label>
            <input type="text" name="variants[${variantCount}][variant_name]" placeholder="e.g. 50ml Rose"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Price (₱)</label>
            <input type="number" name="variants[${variantCount}][price]" step="0.01" min="0"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Stock</label>
            <input type="number" name="variants[${variantCount}][stock]" min="0"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Variant Image</label>
            <input type="file" name="variants[${variantCount}][image]" accept="image/*"
                class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100"
                onchange="previewVariantImage(event, 'vpreview-${variantCount}')">
            <img id="vpreview-${variantCount}" src="" alt="Preview" class="hidden mt-1 h-14 w-14 object-cover rounded-lg border border-gray-200">
        </div>
        <button type="button" onclick="this.closest('.variant-item').remove()" class="absolute top-2 right-2 text-blue-800 hover:text-blue-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(newVariant);
    variantCount++;
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/admin/products/create.blade.php ENDPATH**/ ?>