<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Expense;
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
            $date = $request->get('date', today()->format('Y-m-d'));

            $sales = Sale::whereDate('sale_time', $date)->sum('total_price');
            $expenses = Expense::whereDate('expense_date', $date)->sum('amount');
            $cogs = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->whereDate('sales.sale_time', $date)
                ->sum(DB::raw('sale_items.purchase_price * sale_items.quantity'));
            $grossProfit = $sales - $cogs;
            $netProfit = $grossProfit - $expenses;

            $topProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->whereDate('sales.sale_time', $date)
                ->select('products.id', 'products.name', DB::raw('SUM(sale_items.quantity) as quantity_sold'), DB::raw('SUM(sale_items.line_total) as revenue'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('quantity_sold')
                ->take(5)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Daily report retrieved successfully',
                'data' => [
                    'date' => $date,
                    'sales' => $sales,
                    'cogs' => $cogs,
                    'gross_profit' => $grossProfit,
                    'expenses' => $expenses,
                    'net_profit' => $netProfit,
                    'top_products' => $topProducts
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
            $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfWeek()->format('Y-m-d')));
            $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfWeek()->format('Y-m-d')));

            $sales = Sale::whereBetween('sale_time', [$startDate, $endDate->copy()->endOfDay()])->sum('total_price');
            $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
            $profit = $sales - $expenses;

            $dailyData = [];
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $daySales = Sale::whereDate('sale_time', $currentDate)->sum('total_price');
                $dayExpenses = Expense::whereDate('expense_date', $currentDate)->sum('amount');

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
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d')
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

            $sales = Sale::whereBetween('sale_time', [$startDate, $endDate])->sum('total_price');
            $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
            $profit = $sales - $expenses;

            $weeklyData = [];
            $currentWeek = $startDate->copy()->startOfWeek();

            while ($currentWeek <= $endDate) {
                $weekEnd = $currentWeek->copy()->endOfWeek();
                $weekSales = Sale::whereBetween('sale_time', [$currentWeek, $weekEnd])->sum('total_price');
                $weekExpenses = Expense::whereBetween('expense_date', [$currentWeek, $weekEnd])->sum('amount');

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
     * Get profit and loss report (includes COGS, not just expenses)
     */
    public function profitLoss(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

            $sales = Sale::whereBetween('sale_time', [$startDate, $endDate . ' 23:59:59'])->sum('total_price');
            $cogs = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->whereBetween('sales.sale_time', [$startDate, $endDate . ' 23:59:59'])
                ->sum(DB::raw('sale_items.purchase_price * sale_items.quantity'));
            $grossProfit = $sales - $cogs;
            $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
            $netProfit = $grossProfit - $expenses;

            $expenseBreakdown = Expense::whereBetween('expense_date', [$startDate, $endDate])
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
                    'cost_of_goods_sold' => $cogs,
                    'gross_profit' => $grossProfit,
                    'expenses' => [
                        'total_expenses' => $expenses,
                        'breakdown' => $expenseBreakdown
                    ],
                    'profit_loss' => [
                        'net_profit' => $netProfit,
                        'profit_margin' => $sales > 0 ? ($netProfit / $sales) * 100 : 0
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
