<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index()
    {
        try {
            $today = Carbon::today();

            $totalProducts = Product::count();
            $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();

            $todaySales = Sale::whereDate('sale_time', $today)->sum('total_price');
            $todayCogs = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->whereDate('sales.sale_time', $today)
                ->sum(DB::raw('sale_items.purchase_price * sale_items.quantity'));
            $todayExpenses = Expense::whereDate('expense_date', $today)->sum('amount');

            $totalUsers = User::count();

            // Recent sales (last 5)
            $recentSales = Sale::with(['customer', 'items.product'])
                ->latest('sale_time')
                ->take(5)
                ->get()
                ->map(function ($sale) {
                    return [
                        'id' => $sale->id,
                        'transaction_code' => $sale->transaction_code,
                        'customer_name' => $sale->customer->name ?? 'Walk-in Customer',
                        'total_price' => $sale->total_price,
                        'sale_time' => $sale->sale_time->format('Y-m-d H:i:s'),
                        'items_count' => $sale->items->count()
                    ];
                });

            // Monthly sales chart data (last 6 months)
            $monthlySales = Sale::selectRaw('MONTH(sale_time) as month, YEAR(sale_time) as year, SUM(total_price) as total')
                ->where('sale_time', '>=', Carbon::now()->subMonths(6))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                        'total' => $item->total
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Dashboard data retrieved successfully',
                'data' => [
                    'statistics' => [
                        'total_products' => $totalProducts,
                        'low_stock_products' => $lowStockProducts,
                        'today_sales' => $todaySales,
                        'today_cogs' => $todayCogs,
                        'today_gross_profit' => $todaySales - $todayCogs,
                        'today_expenses' => $todayExpenses,
                        'total_users' => $totalUsers,
                        'net_income_today' => ($todaySales - $todayCogs) - $todayExpenses
                    ],
                    'recent_sales' => $recentSales,
                    'monthly_sales_chart' => $monthlySales,
                    'last_updated' => now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
