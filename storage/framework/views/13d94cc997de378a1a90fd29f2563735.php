<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <?php echo e(__('Account')); ?>

    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.update-profile-information-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.update-password-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.delete-user-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl">
                <div class="max-w-xl">
                    <h3 class="text-base font-semibold text-gray-900"><?php echo e(__('Sign Out')); ?></h3>
                    <p class="mt-1 text-sm text-gray-600"><?php echo e(__('End your current session securely.')); ?></p>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-4">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-md hover:bg-slate-900" style="border-radius: 0.375rem !important;">
                            <?php echo e(__('Log Out')); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\skincare\resources\views/profile/edit.blade.php ENDPATH**/ ?>