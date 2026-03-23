<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = (int) now()->year;

        $filters = $request->validate([
            'year' => 'nullable|integer|min:2000|max:' . ($currentYear + 1),
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $selectedYear = (int) ($filters['year'] ?? $currentYear);

        $startDate = isset($filters['start_date'])
            ? Carbon::parse($filters['start_date'])->startOfDay()
            : now()->subDays(119)->startOfDay();

        $endDate = isset($filters['end_date'])
            ? Carbon::parse($filters['end_date'])->endOfDay()
            : now()->endOfDay();

        // Yearly sales (monthly totals for selected year)
        $yearlyRows = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('MONTH(orders.created_at) as month_number, SUM(order_items.unit_price * order_items.quantity) as total_sales')
            ->where('orders.status', 'completed')
            ->whereYear('orders.created_at', $selectedYear)
            ->groupBy(DB::raw('MONTH(orders.created_at)'))
            ->orderBy(DB::raw('MONTH(orders.created_at)'))
            ->get()
            ->keyBy('month_number');

        $yearlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $yearlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $yearlyData[] = isset($yearlyRows[$month]) ? (float) $yearlyRows[$month]->total_sales : 0;
        }

        // Date-range sales (daily totals)
        $rangeRows = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('DATE(orders.created_at) as sale_date, SUM(order_items.unit_price * order_items.quantity) as total_sales')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(orders.created_at)'))
            ->orderBy(DB::raw('DATE(orders.created_at)'))
            ->get()
            ->keyBy('sale_date');

        $rangeLabels = [];
        $rangeData = [];

        foreach (CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate->copy()->startOfDay()) as $date) {
            $dateKey = $date->format('Y-m-d');
            $rangeLabels[] = $date->format('M d');
            $rangeData[] = isset($rangeRows[$dateKey]) ? (float) $rangeRows[$dateKey]->total_sales : 0;
        }

        // Pie chart: product contribution in selected date range
        $productRows = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->selectRaw('products.name as product_name, SUM(order_items.quantity) as units_sold, SUM(order_items.unit_price * order_items.quantity) as total_sales')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sales')
            ->get();

        $pieLabels = $productRows->pluck('product_name')->toArray();
        $pieData = $productRows->pluck('total_sales')->map(fn ($value) => (float) $value)->toArray();

        $totalCompletedOrders = Order::query()
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalUnitsSold = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_items.quantity');

        $topProducts = $productRows->map(function ($row) {
            return [
                'name' => $row->product_name,
                'units_sold' => (int) $row->units_sold,
                'total_sales' => (float) $row->total_sales,
            ];
        })->toArray();

        // Variant-level sales breakdown (separate from product totals)
        $variantRows = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->selectRaw('products.name as product_name, product_variants.variant_name as variant_name, SUM(order_items.quantity) as units_sold, SUM(order_items.unit_price * order_items.quantity) as total_sales')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('product_variants.id', 'products.name', 'product_variants.variant_name')
            ->orderByDesc('total_sales')
            ->get();

        $topVariants = $variantRows->map(function ($row) {
            return [
                'product_name' => $row->product_name,
                'variant_name' => $row->variant_name ?: 'Default Variant',
                'units_sold' => (int) $row->units_sold,
                'total_sales' => (float) $row->total_sales,
            ];
        })->toArray();

        $yearlyTotal = array_sum($yearlyData);
        $rangeTotal = array_sum($rangeData);

        return view('admin.analytics.sales', [
            'selectedYear' => $selectedYear,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'yearlyLabels' => $yearlyLabels,
            'yearlyData' => $yearlyData,
            'rangeLabels' => $rangeLabels,
            'rangeData' => $rangeData,
            'pieLabels' => $pieLabels,
            'pieData' => $pieData,
            'yearlyTotal' => $yearlyTotal,
            'rangeTotal' => $rangeTotal,
            'totalCompletedOrders' => $totalCompletedOrders,
            'totalUnitsSold' => (int) $totalUnitsSold,
            'topProducts' => $topProducts,
            'topVariants' => $topVariants,
        ]);
    }
}
