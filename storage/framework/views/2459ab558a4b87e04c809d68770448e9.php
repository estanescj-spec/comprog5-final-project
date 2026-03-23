<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title>FLEUR DE PEAU | Online Skincare</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-b from-blue-50 via-white to-amber-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-8">
            <div class="text-center space-y-2">
                <a href="/" class="inline-flex flex-col items-center">
                    <span class="tracking-[0.3em] text-xs text-blue-500 font-medium">FLEUR DE PEAU</span>
                    <span class="mt-1 text-2xl font-semibold text-gray-900">Online Skincare Atelier</span>
                </a>
                <p class="text-xs text-gray-500">Gentle rituals. Radiant skin.</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white/90 backdrop-blur shadow-lg border border-blue-100 overflow-hidden sm:rounded-2xl">
                <?php echo e($slot); ?>

            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\skincare\resources\views/layouts/guest.blade.php ENDPATH**/ ?>