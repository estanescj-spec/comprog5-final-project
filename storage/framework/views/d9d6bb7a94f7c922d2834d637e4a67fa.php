<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title>FLEUR DE PEAU | Dashboard</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <?php echo $__env->yieldContent('styles'); ?>

        <style>
            :root {
                --lux-bg: #f6f8fc;
                --lux-text: #0f172a;
                --lux-border: #e2e8f0;
                --lux-shadow: 0 14px 36px -26px rgba(15, 23, 42, 0.55);
                --lux-shadow-soft: 0 10px 24px -20px rgba(15, 23, 42, 0.45);
            }

            body {
                color: var(--lux-text);
                background:
                    radial-gradient(1200px 500px at 100% -10%, rgba(59, 130, 246, 0.10), transparent 60%),
                    radial-gradient(900px 420px at -10% -20%, rgba(148, 163, 184, 0.14), transparent 60%),
                    var(--lux-bg);
            }

            main {
                padding-bottom: 1.5rem;
            }

            /* Reduce all rounded corners to slight radius */

            .rounded,
            .rounded-sm,
            .rounded-md,
            .rounded-lg,
            .rounded-xl,
            .rounded-2xl,
            .rounded-3xl,
            .rounded-full {
                border-radius: 0 !important;
            }

            /* Premium card polish */
            .bg-white.rounded-2xl,
            .bg-white.rounded-xl,
            .bg-white.rounded-lg,
            .bg-white.rounded-md {
                border: 1px solid var(--lux-border);
                box-shadow: var(--lux-shadow-soft) !important;
            }

            /* Cleaner form fields */
            input[type="text"],
            input[type="email"],
            input[type="number"],
            input[type="password"],
            input[type="search"],
            select,
            textarea {
                border-color: #cbd5e1 !important;
                background: #ffffff;
                color: #0f172a;
            }

            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="number"]:focus,
            input[type="password"]:focus,
            input[type="search"]:focus,
            select:focus,
            textarea:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.14);
            }

            /* Global button readability */
            button,
            input[type="submit"],
            input[type="button"],
            a.inline-flex,
            a.block.text-center {
                font-size: 0.925rem;
                font-weight: 600;
                letter-spacing: 0.01em;
                transition: all 0.18s ease;
            }

            button[class*="px-"],
            input[type="submit"][class*="px-"],
            input[type="button"][class*="px-"],
            a.inline-flex[class*="px-"],
            a.block.text-center[class*="px-"] {
                min-height: 2.5rem;
            }

            button:hover,
            input[type="submit"]:hover,
            input[type="button"]:hover,
            a.inline-flex:hover,
            a.block.text-center:hover {
                transform: translateY(-1px);
                box-shadow: var(--lux-shadow);
            }

            /* Global DataTables UI cleanup */
            .dataTables_wrapper {
                font-size: 0.875rem;
                color: #334155;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 0.75rem;
            }

            .dataTables_wrapper .dataTables_length label,
            .dataTables_wrapper .dataTables_filter label,
            .dataTables_wrapper .dataTables_info {
                color: #64748b;
                font-size: 0.8125rem;
            }

            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid #cbd5e1 !important;
                border-radius: 9999px;
                padding: 0.35rem 0.75rem;
                background: #fff;
                color: #0f172a;
                outline: none;
            }

            .dataTables_wrapper .dataTables_filter input:focus,
            .dataTables_wrapper .dataTables_length select:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
            }

            table.dataTable thead th {
                background: #f8fafc;
                color: #1e293b;
                font-weight: 600;
                border-bottom: 1px solid #e2e8f0 !important;
            }

            table.dataTable tbody td {
                border-bottom: 1px solid #f1f5f9;
            }

            table.dataTable tbody tr:hover {
                background: #f8fbff;
            }

            .dataTables_wrapper .dataTables_paginate {
                margin-top: 0.75rem;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border: 1px solid #dbeafe !important;
                background: #fff !important;
                color: #1e40af !important;
                border-radius: 9999px;
                padding: 0.25rem 0.65rem !important;
                margin: 0 0.1rem;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background: #3b82f6 !important;
                border-color: #3b82f6 !important;
                color: #fff !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
                background: #eff6ff !important;
                border-color: #93c5fd !important;
                color: #1d4ed8 !important;
            }
        </style>

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-gradient-to-b from-blue-50 via-white to-blue-100">
        <div class="min-h-screen">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Heading -->
            <?php if (! empty(trim($__env->yieldContent('header')))): ?>
                <header class="bg-white/90 backdrop-blur border-b border-slate-200">
                    <div class="w-full max-w-[95rem] mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo $__env->yieldContent('header'); ?>
                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main>
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>

        <!-- Toast Notifications -->
        <?php if(session('success')): ?>
        <div id="toast-success" class="fixed top-6 right-6 z-[60] flex items-center gap-3 bg-white border border-green-200 text-green-800 px-5 py-4 rounded-2xl shadow-lg max-w-sm transition-all duration-500" style="border-radius: 0.5rem !important;" role="alert">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-700" style="border-radius: 0.375rem !important;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div id="toast-error" class="fixed top-6 right-6 z-[60] flex items-center gap-3 bg-white border border-red-200 text-red-800 px-5 py-4 rounded-2xl shadow-lg max-w-sm transition-all duration-500" style="border-radius: 0.5rem !important;" role="alert">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
            </svg>
            <span class="text-sm font-medium"><?php echo e(session('error')); ?></span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-700" style="border-radius: 0.375rem !important;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <?php endif; ?>

        <!-- Confirm Modal -->
        <div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4" style="border-radius: 0.5rem !important;">
                <h3 class="text-lg font-semibold text-gray-900 mb-2" id="confirm-modal-title">Are you sure?</h3>
                <p class="text-sm text-gray-500 mb-6" id="confirm-modal-message">This action cannot be undone.</p>
                <div class="flex justify-end gap-3">
                    <button onclick="closeConfirmModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-full hover:bg-gray-200" style="border-radius: 0.375rem !important;">Cancel</button>
                    <button id="confirm-modal-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">Confirm</button>
                </div>
            </div>
        </div>

        <script>
        function openConfirmModal(message, onConfirm, confirmLabel) {
            document.getElementById('confirm-modal-message').textContent = message;
            document.getElementById('confirm-modal-btn').textContent = confirmLabel || 'Confirm';
            document.getElementById('confirm-modal').classList.remove('hidden');
            document.getElementById('confirm-modal').classList.add('flex');
            document.getElementById('confirm-modal-btn').onclick = function() {
                closeConfirmModal();
                onConfirm();
            };
        }
        function closeConfirmModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
            document.getElementById('confirm-modal').classList.remove('flex');
        }

        function setupImageUploadPreviews() {
            const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');

            imageInputs.forEach(function(input) {
                let wrapper = input.parentElement?.querySelector('.instant-image-preview-wrap');
                let preview;
                let removeBtn;

                if (!wrapper) {
                    wrapper = document.createElement('div');
                    wrapper.className = 'instant-image-preview-wrap relative mt-3 w-fit hidden';

                    preview = document.createElement('img');
                    preview.className = 'instant-image-preview h-24 w-24 rounded-lg border border-gray-200 object-cover';
                    preview.alt = 'Image preview';

                    removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'instant-image-remove absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-600 text-white text-xs font-bold hover:bg-red-700';
                    removeBtn.style.borderRadius = '50%';
                    removeBtn.setAttribute('aria-label', 'Remove selected image');
                    removeBtn.textContent = '×';

                    wrapper.appendChild(preview);
                    wrapper.appendChild(removeBtn);
                    input.insertAdjacentElement('afterend', wrapper);
                } else {
                    preview = wrapper.querySelector('.instant-image-preview');
                    removeBtn = wrapper.querySelector('.instant-image-remove');
                    if (removeBtn) {
                        removeBtn.style.borderRadius = '50%';
                    }
                }

                removeBtn?.addEventListener('click', function () {
                    input.value = '';
                    if (preview) preview.src = '';
                    wrapper?.classList.add('hidden');
                });

                input.addEventListener('change', function () {
                    const file = this.files && this.files[0];

                    if (!file || !file.type.startsWith('image/')) {
                        if (preview) preview.src = '';
                        wrapper?.classList.add('hidden');
                        return;
                    }

                    const url = URL.createObjectURL(file);
                    preview.src = url;
                    wrapper?.classList.remove('hidden');

                    preview.onload = function () {
                        URL.revokeObjectURL(url);
                    };
                });
            });
        }

        function setupLiveSearchForms() {
            const forms = document.querySelectorAll('form[data-live-search-form]');

            forms.forEach(function (form) {
                const searchInput = form.querySelector('input[data-live-search]');
                if (!searchInput) return;

                let debounceTimer = null;

                searchInput.addEventListener('input', function () {
                    if (debounceTimer) {
                        clearTimeout(debounceTimer);
                    }

                    debounceTimer = setTimeout(function () {
                        form.submit();
                    }, 300);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Auto-dismiss toasts after 4 seconds
            ['toast-success', 'toast-error'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) {
                    setTimeout(function() {
                        el.style.opacity = '0';
                        el.style.transform = 'translateX(100%)';
                        setTimeout(function() { el.remove(); }, 500);
                    }, 4000);
                }
            });

            document.querySelectorAll('[data-confirm]').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var message = btn.getAttribute('data-confirm');
                    var label = btn.getAttribute('data-confirm-label') || 'Confirm';
                    var form = btn.closest('form');
                    openConfirmModal(message, function () { form.submit(); }, label);
                });
            });

            setupImageUploadPreviews();
            setupLiveSearchForms();
        });
        </script>
        <?php echo $__env->yieldContent('scripts'); ?>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/layouts/app.blade.php ENDPATH**/ ?>