<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index(Request $request)
    {
        try {
            $query = Sale::with(['customer', 'products']);
            
            // Pagination
            $perPage = $request->get('per_page', 10);
            $sales = $query->latest()->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sales retrieved successfully',
                'data' => $sales
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve sales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created sale
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,card,mobile_money',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'user_id' => auth()->id(),
            ]);

            // Attach products to sale
            foreach ($request->products as $product) {
                $sale->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price']
                ]);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sale created successfully',
                'data' => $sale->load(['customer', 'products'])
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create sale',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale)
    {
        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Sale retrieved successfully',
                'data' => $sale->load(['customer', 'products'])
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve sale',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified sale
     */
    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'payment_method' => 'sometimes|required|string|in:cash,card,mobile_money',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sale->update($request->all());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sale updated successfully',
                'data' => $sale->load(['customer', 'products'])
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update sale',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified sale
     */
    public function destroy(Sale $sale)
    {
        try {
            $sale->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sale deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete sale',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get today's sales
     */
    public function today()
    {
        try {
            $sales = Sale::with(['customer', 'products'])
                ->whereDate('created_at', today())
                ->latest()
                ->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Today\'s sales retrieved successfully',
                'data' => $sales
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve today\'s sales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sales by date
     */
    public function byDate($date)
    {
        try {
            $sales = Sale::with(['customer', 'products'])
                ->whereDate('created_at', $date)
                ->latest()
                ->get();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Sales for ' . $date . ' retrieved successfully',
                'data' => $sales
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve sales for date',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 