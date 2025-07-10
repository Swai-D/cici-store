<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get dashboard statistics
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();
        $totalSales = Sale::sum('total_price');
        $totalExpenses = Expense::sum('amount');
        $totalProfit = $totalSales - $totalExpenses;
        
        // Get latest sales
        $latestSales = Sale::with('product')->latest()->take(5)->get();
        
        // Get low stock products with pagination (10 items per page)
        $lowStockItems = Product::where('stock_quantity', '<', 10)
            ->orderBy('stock_quantity', 'asc')
            ->paginate(10);
        
        // Prepare sales chart data for last 7 days
        $salesLabels = collect(range(6, 0))->map(function($day) {
            return now()->subDays($day)->format('M d');
        })->toArray();
        $salesData = collect(range(6, 0))->map(function($day) {
            return Sale::whereDate('sale_time', now()->subDays($day))->sum('total_price');
        })->toArray();

        // Prepare product category chart data with colors
        $categoryData = Product::with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function($products) {
                return $products->sum('stock_quantity');
            });
        $categoryLabels = $categoryData->keys()->toArray();
        $categoryValues = $categoryData->values()->toArray();
        
        // Get category colors for the chart
        $categoryColors = [];
        foreach ($categoryLabels as $categoryName) {
            $category = \App\Models\Category::where('name', $categoryName)->first();
            $categoryColors[] = $category && $category->color ? $category->color : '#3B82F6';
        }

        return view('dashboard', compact(
            'totalProducts',
            'lowStockProducts',
            'totalSales',
            'totalExpenses',
            'totalProfit',
            'latestSales',
            'lowStockItems',
            'salesLabels',
            'salesData',
            'categoryLabels',
            'categoryValues',
            'categoryColors'
        ));
    }
}
