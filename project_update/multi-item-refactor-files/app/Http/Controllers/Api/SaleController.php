<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index(Request $request)
    {
        try {
            $query = Sale::with(['items.product', 'customer']);

            $perPage = $request->get('per_page', 10);
            $sales = $query->latest('sale_time')->paginate($perPage);

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
     * Store a newly created sale (multi-item order).
     *
     * Expected payload:
     * items: [ { product_id, quantity, unit_price }, ... ]
     * payment_method, customer_phone, discount_amount, notes
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,M-Pesa,TigoPesa,Airtel Money,Bank,Credit Card',
            'customer_phone' => 'nullable|string|max:20',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $items = $request->input('items');

        try {
            $sale = DB::transaction(function () use ($request, $items) {
                $productIds = collect($items)->pluck('product_id')->unique();
                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $subtotal = 0;
                $lines = [];

                foreach ($items as $line) {
                    $product = $products->get($line['product_id']);

                    if (!$product) {
                        throw new \RuntimeException("Product not found (ID: {$line['product_id']}).");
                    }

                    if ($product->stock_quantity < $line['quantity']) {
                        throw new \RuntimeException("Insufficient stock for: {$product->name}. Available: {$product->stock_quantity}.");
                    }

                    $lineTotal = $line['quantity'] * $line['unit_price'];
                    $subtotal += $lineTotal;

                    $lines[] = [
                        'product' => $product,
                        'quantity' => $line['quantity'],
                        'unit_price' => $line['unit_price'],
                        'purchase_price' => $product->purchase_price,
                        'line_total' => $lineTotal,
                    ];
                }

                $discount = $request->input('discount_amount', 0);
                $totalPrice = $subtotal - $discount;

                $sale = Sale::create([
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'total_price' => $totalPrice,
                    'payment_method' => $request->input('payment_method'),
                    'customer_phone' => $request->input('customer_phone'),
                    'notes' => $request->input('notes'),
                    'sale_time' => now(),
                ]);

                foreach ($lines as $line) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $line['product']->id,
                        'quantity' => $line['quantity'],
                        'unit_price' => $line['unit_price'],
                        'purchase_price' => $line['purchase_price'],
                        'line_total' => $line['line_total'],
                    ]);

                    $line['product']->decrement('stock_quantity', $line['quantity']);
                }

                if ($request->filled('customer_phone')) {
                    $customer = Customer::firstOrCreate(
                        ['phone' => $request->input('customer_phone')],
                        ['name' => $request->input('customer_phone')]
                    );
                    $customer->increment('total_spent', $totalPrice);
                    $customer->update(['last_purchase_date' => now()]);
                }

                return $sale;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Sale created successfully',
                'data' => $sale->load(['items.product', 'customer'])
            ], 201);

        } catch (\RuntimeException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
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
                'data' => $sale->load(['items.product', 'customer'])
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
     * Update the specified sale (order-level fields only — line items are
     * immutable once stock has moved; void + re-create for item changes).
     */
    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'sometimes|required|in:Cash,M-Pesa,TigoPesa,Airtel Money,Bank,Credit Card',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sale->update($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Sale updated successfully',
                'data' => $sale->load(['items.product', 'customer'])
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
     * Remove (void) the specified sale and restore stock.
     */
    public function destroy(Sale $sale)
    {
        try {
            DB::transaction(function () use ($sale) {
                $sale->load('items');

                foreach ($sale->items as $item) {
                    Product::whereKey($item->product_id)->lockForUpdate()->increment('stock_quantity', $item->quantity);
                }

                $sale->items()->delete();
                $sale->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Sale voided and stock restored'
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
            $sales = Sale::with(['items.product', 'customer'])
                ->whereDate('sale_time', today())
                ->latest('sale_time')
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
            $sales = Sale::with(['items.product', 'customer'])
                ->whereDate('sale_time', $date)
                ->latest('sale_time')
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
