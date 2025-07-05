<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses
     */
    public function index(Request $request)
    {
        try {
            $query = Expense::query();
            
            // Pagination
            $perPage = $request->get('per_page', 10);
            $expenses = $query->latest()->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Expenses retrieved successfully',
                'data' => $expenses
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve expenses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created expense
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $expense = Expense::create([
                'description' => $request->description,
                'amount' => $request->amount,
                'category' => $request->category,
                'date' => $request->date,
                'user_id' => auth()->id(),
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Expense created successfully',
                'data' => $expense
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create expense',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified expense
     */
    public function show(Expense $expense)
    {
        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Expense retrieved successfully',
                'data' => $expense
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve expense',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified expense
     */
    public function update(Request $request, Expense $expense)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'category' => 'sometimes|required|string|max:100',
            'date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $expense->update($request->all());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Expense updated successfully',
                'data' => $expense
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update expense',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified expense
     */
    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Expense deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete expense',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get today's expenses
     */
    public function today()
    {
        try {
            $expenses = Expense::whereDate('created_at', today())
                ->latest()
                ->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Today\'s expenses retrieved successfully',
                'data' => $expenses
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve today\'s expenses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get expenses by date
     */
    public function byDate($date)
    {
        try {
            $expenses = Expense::whereDate('created_at', $date)
                ->latest()
                ->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Expenses for ' . $date . ' retrieved successfully',
                'data' => $expenses
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve expenses for date',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 