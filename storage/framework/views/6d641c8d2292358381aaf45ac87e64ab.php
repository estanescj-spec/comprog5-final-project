

<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <?php echo e(__('Sales Analytics')); ?>

    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="<?php echo e(route('admin.analytics.sales')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div>
                    <label for="year" class="block text-xs font-medium text-gray-600 mb-1">Year</label>
                    <input
                        type="number"
                        id="year"
                        name="year"
                        min="2000"
                        max="<?php echo e(now()->year + 1); ?>"
                        value="<?php echo e($selectedYear); ?>"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        style="border-radius: 0.375rem !important;"
                    >
                </div>

                <div>
                    <label for="start_date" class="block text-xs font-medium text-gray-600 mb-1">Start Date</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="<?php echo e($startDate); ?>"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        style="border-radius: 0.375rem !important;"
                    >
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-medium text-gray-600 mb-1">End Date</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="<?php echo e($endDate); ?>"
                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                        style="border-radius: 0.375rem !important;"
                    >
                </div>

                <div class="lg:col-span-2 flex flex-wrap gap-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-full hover:bg-blue-800" style="border-radius: 0.375rem !important;">
                        Apply
                    </button>
                    <a href="<?php echo e(route('admin.analytics.sales')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-full hover:bg-gray-200" style="border-radius: 0.375rem !important;">
                        Reset
                    </a>
                </div>
            </form>
            <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900">Yearly Sales Bar Chart (<?php echo e($selectedYear); ?>)</h3>
            <p class="text-sm text-gray-500 mb-4">Monthly total sales for the selected year.</p>
            <div class="h-80">
                <canvas id="yearlySalesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900">Sales Bar Chart with Date Range</h3>
            <p class="text-sm text-gray-500 mb-4">Daily sales totals between your selected dates.</p>
            <div class="h-80">
                <canvas id="rangeSalesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900">Product Sales Contribution (%)</h3>
            <p class="text-sm text-gray-500 mb-4">Each slice shows a product's share of total sales in the selected date range.</p>
            <?php if(count($pieLabels) === 0): ?>
                <p class="text-sm text-gray-500">No completed sales found in the selected date range.</p>
            <?php else: ?>
                <?php
                    $pieTotal = array_sum($pieData);
                    $pieColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1', '#14b8a6', '#a855f7'];
                ?>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    <div class="h-96">
                        <canvas id="productPieChart"></canvas>
                    </div>
                    <div class="border border-gray-100 rounded-xl p-4 max-h-96 overflow-y-auto">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Product Contribution List</h4>
                        <ul class="space-y-2 text-sm">
                            <?php $__currentLoopData = $pieLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $value = (float) ($pieData[$index] ?? 0);
                                    $percent = $pieTotal > 0 ? ($value / $pieTotal) * 100 : 0;
                                    $color = $pieColors[$index % count($pieColors)];
                                ?>
                                <li class="flex items-center justify-between border-b border-gray-100 pb-2">
                                    <span class="inline-flex items-center gap-2 text-gray-700 mr-3">
                                        <span class="product-color-dot h-3 w-3 rounded-full border border-gray-200" data-color="<?php echo e($color); ?>"></span>
                                        <?php echo e($label); ?>

                                    </span>
                                    <span class="flex flex-col items-end">
                                        <span class="text-gray-900 font-medium"><?php echo e(number_format($percent, 2)); ?>%</span>
                                        <span class="text-xs text-gray-500">₱<?php echo e(number_format($value, 2)); ?></span>
                                    </span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900">Variant Sales Breakdown</h3>
            <p class="text-sm text-gray-500 mb-4">Variant-level totals are listed separately from product totals.</p>

            <?php if(empty($topVariants)): ?>
                <p class="text-sm text-gray-500">No completed variant sales found in the selected date range.</p>
            <?php else: ?>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="min-w-full text-sm" id="variant-breakdown-table">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-2 pr-4">Product</th>
                                <th class="py-2 pr-4">Variant</th>
                                <th class="py-2 pr-4">
                                    <button type="button" onclick="sortVariantRows('units')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 shadow-sm font-medium" style="border-radius: 0.375rem !important;">
                                        Units Sold <span id="units-sort-indicator">↕</span>
                                    </button>
                                </th>
                                <th class="py-2 pr-4">
                                    <button type="button" onclick="sortVariantRows('sales')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 shadow-sm font-medium" style="border-radius: 0.375rem !important;">
                                        Total Sales <span id="sales-sort-indicator">↕</span>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="variant-breakdown-body">
                            <?php $__currentLoopData = $topVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b last:border-0" data-units="<?php echo e($variant['units_sold']); ?>" data-sales="<?php echo e($variant['total_sales']); ?>">
                                    <td class="py-2 pr-4 text-gray-900"><?php echo e($variant['product_name']); ?></td>
                                    <td class="py-2 pr-4 text-gray-700"><?php echo e($variant['variant_name']); ?></td>
                                    <td class="py-2 pr-4 text-gray-700"><?php echo e(number_format($variant['units_sold'])); ?></td>
                                    <td class="py-2 pr-4 text-gray-700">₱<?php echo e(number_format($variant['total_sales'], 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script id="sales-chart-data" type="application/json"><?php echo json_encode([
    'yearlyLabels' => $yearlyLabels,
    'yearlyData' => $yearlyData,
    'rangeLabels' => $rangeLabels,
    'rangeData' => $rangeData,
    'pieLabels' => $pieLabels,
    'pieData' => $pieData,
    'pieColors' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1', '#14b8a6', '#a855f7'],
]); ?></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartDataElement = document.getElementById('sales-chart-data');
    const chartData = chartDataElement ? JSON.parse(chartDataElement.textContent) : {
        yearlyLabels: [],
        yearlyData: [],
        rangeLabels: [],
        rangeData: [],
        pieLabels: [],
        pieData: [],
    };

    const yearlyLabels = chartData.yearlyLabels;
    const yearlyData = chartData.yearlyData;
    const rangeLabels = chartData.rangeLabels;
    const rangeData = chartData.rangeData;
    const pieLabels = chartData.pieLabels;
    const pieData = chartData.pieData;
    const pieColors = chartData.pieColors || [];

    document.querySelectorAll('.product-color-dot').forEach((el) => {
        const color = el.getAttribute('data-color');
        if (color) {
            el.style.backgroundColor = color;
        }
    });

    const pesoFormat = (value) => '₱' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    new Chart(document.getElementById('yearlySalesChart'), {
        type: 'bar',
        data: {
            labels: yearlyLabels,
            datasets: [{
                label: 'Sales',
                data: yearlyData,
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(29, 78, 216, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (ctx) => ' ' + pesoFormat(ctx.parsed.y)
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 0,
                        autoSkip: true,
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => pesoFormat(value)
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('rangeSalesChart'), {
        type: 'bar',
        data: {
            labels: rangeLabels,
            datasets: [{
                label: 'Sales',
                data: rangeData,
                backgroundColor: 'rgba(16, 185, 129, 0.6)',
                borderColor: 'rgba(5, 150, 105, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (ctx) => ' ' + pesoFormat(ctx.parsed.y)
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 0,
                        autoSkip: true,
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => pesoFormat(value)
                    }
                }
            }
        }
    });

    if (pieLabels.length > 0) {
        new Chart(document.getElementById('productPieChart'), {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: pieColors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const dataset = ctx.dataset.data;
                                const total = dataset.reduce((sum, value) => sum + Number(value), 0);
                                const value = Number(ctx.raw);
                                const percent = total > 0 ? ((value / total) * 100).toFixed(2) : '0.00';
                                return ` ${pesoFormat(value)} (${percent}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    const sortState = { by: null, direction: 'desc' };

    function sortVariantRows(by) {
        const tbody = document.getElementById('variant-breakdown-body');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (rows.length === 0) return;

        if (sortState.by === by) {
            sortState.direction = sortState.direction === 'desc' ? 'asc' : 'desc';
        } else {
            sortState.by = by;
            sortState.direction = 'desc';
        }

        rows.sort((a, b) => {
            const aValue = Number(a.dataset[by] || 0);
            const bValue = Number(b.dataset[by] || 0);
            return sortState.direction === 'desc' ? bValue - aValue : aValue - bValue;
        });

        rows.forEach((row) => tbody.appendChild(row));

        const unitsIndicator = document.getElementById('units-sort-indicator');
        const salesIndicator = document.getElementById('sales-sort-indicator');

        if (unitsIndicator) unitsIndicator.textContent = by === 'units' ? (sortState.direction === 'desc' ? '↓' : '↑') : '↕';
        if (salesIndicator) salesIndicator.textContent = by === 'sales' ? (sortState.direction === 'desc' ? '↓' : '↑') : '↕';
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\comprog5-final-project-main\resources\views/admin/analytics/sales.blade.php ENDPATH**/ ?>