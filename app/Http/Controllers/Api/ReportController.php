<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Get daily report
     */
    public function daily(Request $request)
    {
        try {
            $date = $request->get('date', today());
            
            $sales = Sale::whereDate('created_at', $date)->sum('total_amount');
            $expenses = Expense::whereDate('created_at', $date)->sum('amount');
            $profit = $sales - $expenses;
            
            $topProducts = Sale::whereDate('created_at', $date)
                ->with('products')
                ->get()
                ->flatMap(function ($sale) {
                    return $sale->products;
                })
                ->groupBy('id')
                ->map(function ($products) {
                    return [
                        'product' => $products->first(),
                        'quantity_sold' => $products->sum('pivot.quantity')
                    ];
                })
                ->sortByDesc('quantity_sold')
                ->take(5);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Daily report retrieved successfully',
                'data' => [
                    'date' => $date,
                    'sales' => $sales,
                    'expenses' => $expenses,
                    'profit' => $profit,
                    'top_products' => $topProducts->values()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve daily report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get weekly report
     */
    public function weekly(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->startOfWeek());
            $endDate = $request->get('end_date', Carbon::now()->endOfWeek());
            
            $sales = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
            $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
            $profit = $sales - $expenses;
            
            // Daily breakdown
            $dailyData = [];
            $currentDate = Carbon::parse($startDate);
            
            while ($currentDate <= Carbon::parse($endDate)) {
                $daySales = Sale::whereDate('created_at', $currentDate)->sum('total_amount');
                $dayExpenses = Expense::whereDate('created_at', $currentDate)->sum('amount');
                
                $dailyData[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'sales' => $daySales,
                    'expenses' => $dayExpenses,
                    'profit' => $daySales - $dayExpenses
                ];
                
                $currentDate->addDay();
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Weekly report retrieved successfully',
                'data' => [
                    'period' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ],
                    'total_sales' => $sales,
                    'total_expenses' => $expenses,
                    'total_profit' => $profit,
                    'daily_breakdown' => $dailyData
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve weekly report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get monthly report
     */
    public function monthly(Request $request)
    {
        try {
            $year = $request->get('year', Carbon::now()->year);
            $month = $request->get('month', Carbon::now()->month);
            
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            $sales = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
            $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
            $profit = $sales - $expenses;
            
            // Weekly breakdown
            $weeklyData = [];
            $currentWeek = $startDate->copy()->startOfWeek();
            
            while ($currentWeek <= $endDate) {
                $weekEnd = $currentWeek->copy()->endOfWeek();
                $weekSales = Sale::whereBetween('created_at', [$currentWeek, $weekEnd])->sum('total_amount');
                $weekExpenses = Expense::whereBetween('created_at', [$currentWeek, $weekEnd])->sum('amount');
                
                $weeklyData[] = [
                    'week' => $currentWeek->format('Y-W'),
                    'start_date' => $currentWeek->format('Y-m-d'),
                    'end_date' => $weekEnd->format('Y-m-d'),
                    'sales' => $weekSales,
                    'expenses' => $weekExpenses,
                    'profit' => $weekSales - $weekExpenses
                ];
                
                $currentWeek->addWeek();
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Monthly report retrieved successfully',
                'data' => [
                    'period' => [
                        'year' => $year,
                        'month' => $month,
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d')
                    ],
                    'total_sales' => $sales,
                    'total_expenses' => $expenses,
                    'total_profit' => $profit,
                    'weekly_breakdown' => $weeklyData
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve monthly report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get profit and loss report
     */
    public function profitLoss(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
            
            $sales = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
            $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
            $profit = $sales - $expenses;
            
            // Expense breakdown by category
            $expenseBreakdown = Expense::whereBetween('created_at', [$startDate, $endDate])
                ->select('category', DB::raw('SUM(amount) as total'))
                ->groupBy('category')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Profit and loss report retrieved successfully',
                'data' => [
                    'period' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ],
                    'revenue' => [
                        'total_sales' => $sales
                    ],
                    'expenses' => [
                        'total_expenses' => $expenses,
                        'breakdown' => $expenseBreakdown
                    ],
                    'profit_loss' => [
                        'gross_profit' => $profit,
                        'profit_margin' => $sales > 0 ? ($profit / $sales) * 100 : 0
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve profit and loss report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get profit and loss report for custom date range
     */
    public function profitLossRange(Request $request)
    {
        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            if (!$startDate || !$endDate) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Start date and end date are required'
                ], 422);
            }
            
            return $this->profitLoss($request);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve profit and loss report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 