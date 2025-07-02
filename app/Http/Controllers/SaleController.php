<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['product.category']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('sale_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sale_time', '<=', $request->date_to);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->latest('sale_time')->paginate(15);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('stock_quantity', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_sold' => 'required|integer|min:1',
            'sale_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,M-Pesa,TigoPesa,Bank',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if enough stock is available
        if ($product->stock_quantity < $request->quantity_sold) {
            return back()->withErrors(['quantity_sold' => 'Insufficient stock available.'])->withInput();
        }

        // Calculate total price
        $totalPrice = $request->quantity_sold * $request->sale_price;

        // Generate transaction code
        $transactionCode = 'TXN' . date('Ymd') . str_pad(Sale::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        $sale = Sale::create([
            'transaction_code' => $transactionCode,
            'product_id' => $request->product_id,
            'quantity_sold' => $request->quantity_sold,
            'sale_price' => $request->sale_price,
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'customer_phone' => $request->customer_phone,
            'sale_time' => now(),
        ]);

        // Update product stock
        $product->decrement('stock_quantity', $request->quantity_sold);

        return redirect()->route('web.sales.index')->with('success', 'Sale recorded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load(['product.category', 'product.supplier']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity_sold' => 'required|integer|min:1',
            'sale_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,M-Pesa,TigoPesa,Bank',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if enough stock is available (considering the current sale)
        $availableStock = $product->stock_quantity + $sale->quantity_sold;
        if ($availableStock < $request->quantity_sold) {
            return back()->withErrors(['quantity_sold' => 'Insufficient stock available.']);
        }

        // Calculate total price
        $totalPrice = $request->quantity_sold * $request->sale_price;

        // Update stock (restore old quantity and subtract new quantity)
        $product->increment('stock_quantity', $sale->quantity_sold);
        $product->decrement('stock_quantity', $request->quantity_sold);

        $sale->update([
            'product_id' => $request->product_id,
            'quantity_sold' => $request->quantity_sold,
            'sale_price' => $request->sale_price,
            'total_price' => $totalPrice,
            'payment_method' => $request->payment_method,
            'customer_phone' => $request->customer_phone,
        ]);

        return redirect()->route('web.sales.index')->with('success', 'Sale updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        // Restore stock quantity
        $sale->product->increment('stock_quantity', $sale->quantity_sold);
        
        $sale->delete();
        return redirect()->route('web.sales.index')->with('success', 'Sale deleted successfully!');
    }
}
