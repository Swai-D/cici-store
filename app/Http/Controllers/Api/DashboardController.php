<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
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
            // Today's date
            $today = Carbon::today();
            
            // Total products
            $totalProducts = Product::count();
            
            // Low stock products (less than 10 items)
            $lowStockProducts = Product::where('quantity', '<', 10)->count();
            
            // Today's sales
            $todaySales = Sale::whereDate('created_at', $today)->sum('total_amount');
            
            // Today's expenses
            $todayExpenses = Expense::whereDate('created_at', $today)->sum('amount');
            
            // Total users
            $totalUsers = User::count();
            
            // Recent sales (last 5)
            $recentSales = Sale::with(['customer', 'products'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($sale) {
                    return [
                        'id' => $sale->id,
                        'customer_name' => $sale->customer->name ?? 'Walk-in Customer',
                        'total_amount' => $sale->total_amount,
                        'created_at' => $sale->created_at->format('Y-m-d H:i:s'),
                        'products_count' => $sale->products->count()
                    ];
                });
            
            // Monthly sales chart data (last 6 months)
            $monthlySales = Sale::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_amount) as total')
                ->where('created_at', '>=', Carbon::now()->subMonths(6))
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
                        'today_expenses' => $todayExpenses,
                        'total_users' => $totalUsers,
                        'net_income_today' => $todaySales - $todayExpenses
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