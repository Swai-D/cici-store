<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get dashboard statistics
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();
        $totalSales = Sale::sum('total_price');
        $totalExpenses = Expense::sum('amount');

        // COGS (Cost of Goods Sold) = gharama halisi ya bidhaa zilizouzwa
        // (purchase_price ya bidhaa x quantity iliyouzwa), sio bei ya kuuzia.
        $totalCogs = DB::table('sale_items')
            ->sum(DB::raw('sale_items.purchase_price * sale_items.quantity'));

        // Gross Profit = Mauzo - COGS (faida kabla ya expenses za uendeshaji)
        $grossProfit = $totalSales - $totalCogs;

        // Net Profit = Gross Profit - Operating Expenses (faida halisi)
        $totalProfit = $grossProfit - $totalExpenses;
        
        // Get latest sales
        $latestSales = Sale::with('items.product')->latest('sale_time')->take(5)->get();
        
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
            'totalCogs',
            'grossProfit',
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
