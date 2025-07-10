<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function daily(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        
        $sales = Sale::whereDate('sale_time', $date)
            ->with('product')
            ->get();

        $totalSales = $sales->sum('total_price');
        $totalTransactions = $sales->count();
        $totalProfit = $sales->sum('profit');
        
        // Most sold product - handle null products
        $mostSoldProduct = $sales->groupBy(function($sale) {
            return $sale->product ? $sale->product->name : 'Unknown Product';
        })
            ->map(function($productSales) {
                return $productSales->sum('quantity_sold');
            })
            ->sortDesc()
        ->keys()
            ->first();

        // Sales by hour - handle potential null sale_time
        $salesByHour = $sales->groupBy(function($sale) {
            return $sale->sale_time ? $sale->sale_time->format('H') : '00';
        })->map(function($hourSales) {
            return $hourSales->sum('total_price');
        });

        // Prepare chart data for Chart.js
        $chartData = [
            'labels' => $salesByHour->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Sales (Tsh)',
                    'data' => $salesByHour->values()->toArray(),
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'tension' => 0.1
                ]
            ]
        ];

        return view('reports.daily', compact(
            'date', 'sales', 'totalSales', 'totalTransactions', 
            'totalProfit', 'mostSoldProduct', 'chartData'
        ));
    }

    public function weekly(Request $request)
    {
        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));
        $weekEnd = $request->get('week_end', now()->endOfWeek()->format('Y-m-d'));

        $sales = Sale::whereBetween('sale_time', [$weekStart, $weekEnd])
            ->with('product')
            ->get();

        $totalSales = $sales->sum('total_price');
        $totalTransactions = $sales->count();
        $totalProfit = $sales->sum('profit');

        // Top selling products - handle null products
        $topProducts = $sales->groupBy(function($sale) {
            return $sale->product ? $sale->product->name : 'Unknown Product';
        })
            ->map(function($productSales) {
                return [
                    'quantity' => $productSales->sum('quantity_sold'),
                    'revenue' => $productSales->sum('total_price'),
                    'profit' => $productSales->sum('profit'),
                ];
            })
            ->sortByDesc('revenue')
            ->take(5);

        // Sales by day - handle potential null sale_time
        $salesByDay = $sales->groupBy(function($sale) {
            return $sale->sale_time ? $sale->sale_time->format('D') : 'Unknown';
        })->map(function($daySales) {
            return $daySales->sum('total_price');
        });

        // Prepare chart data for Chart.js
        $chartData = [
            'labels' => $salesByDay->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Sales (Tsh)',
                    'data' => $salesByDay->values()->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ]
            ]
        ];

        return view('reports.weekly', compact(
            'weekStart', 'weekEnd', 'sales', 'totalSales', 'totalTransactions',
            'totalProfit', 'topProducts', 'chartData'
        ));
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);

        $sales = Sale::whereYear('sale_time', $year)
            ->whereMonth('sale_time', $monthNum)
            ->with('product')
            ->get();

        $totalSales = $sales->sum('total_price');
        $totalTransactions = $sales->count();
        $totalProfit = $sales->sum('profit');

        // Expenses for the month
        $expenses = Expense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum)
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalProfit - $totalExpenses;

        // Top selling products - handle null products
        $topProducts = $sales->groupBy(function($sale) {
            return $sale->product ? $sale->product->name : 'Unknown Product';
        })
            ->map(function($productSales) {
                return [
                    'quantity' => $productSales->sum('quantity_sold'),
                    'revenue' => $productSales->sum('total_price'),
                    'profit' => $productSales->sum('profit'),
                ];
            })
            ->sortByDesc('revenue')
            ->take(10);

        // Sales by day - handle potential null sale_time
        $salesByDay = $sales->groupBy(function($sale) {
            return $sale->sale_time ? $sale->sale_time->format('d') : '00';
        })->map(function($daySales) {
            return $daySales->sum('total_price');
        });

        // Expenses by category
        $expensesByCategory = $expenses->groupBy('category')
            ->map(function($categoryExpenses) {
                return $categoryExpenses->sum('amount');
            });

        // Prepare chart data for Chart.js
        $salesChartData = [
            'labels' => $salesByDay->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Sales (Tsh)',
                    'data' => $salesByDay->values()->toArray(),
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'tension' => 0.1
                ]
            ]
        ];

        $expensesChartData = [
            'labels' => $expensesByCategory->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Amount (Tsh)',
                    'data' => $expensesByCategory->values()->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(199, 199, 199, 0.2)',
                        'rgba(83, 102, 255, 0.2)',
                        'rgba(78, 252, 3, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)',
                        'rgba(83, 102, 255, 1)',
                        'rgba(78, 252, 3, 1)',
                    ],
                    'borderWidth' => 1
                ]
            ]
        ];

        return view('reports.monthly', compact(
            'month', 'sales', 'expenses', 'totalSales', 'totalTransactions',
            'totalProfit', 'totalExpenses', 'netProfit', 'topProducts',
            'salesChartData', 'expensesChartData'
        ));
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $sales = Sale::whereBetween('sale_time', [$startDate, $endDate])->get();
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get();

        $totalSales = $sales->sum('total_price');
        $totalCost = $sales->sum(function($sale) {
            return $sale->product->purchase_price * $sale->quantity_sold;
        });
        $grossProfit = $totalSales - $totalCost;
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $grossProfit - $totalExpenses;

        // Expenses breakdown
        $expensesByCategory = $expenses->groupBy('category')
            ->map(function($categoryExpenses) {
                return $categoryExpenses->sum('amount');
            });

        // Sales by product category
        $salesByCategory = $sales->groupBy('product.category.name')
            ->map(function($categorySales) {
                return [
                    'sales' => $categorySales->sum('total_price'),
                    'cost' => $categorySales->sum(function($sale) {
                        return $sale->product->purchase_price * $sale->quantity_sold;
                    }),
                    'profit' => $categorySales->sum('profit'),
                ];
            });

        return view('reports.profit-loss', compact(
            'startDate', 'endDate', 'totalSales', 'totalCost', 'grossProfit',
            'totalExpenses', 'netProfit', 'expensesByCategory', 'salesByCategory'
        ));
    }
}
