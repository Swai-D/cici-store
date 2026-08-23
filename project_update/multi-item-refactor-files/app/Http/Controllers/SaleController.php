<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['items.product.category']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('items.product', function ($productQuery) use ($search) {
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

        $sales = $query->latest('sale_time')->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource (multi-item cart).
     */
    public function create()
    {
        return view('sales.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * Expected payload:
     * items: [ { product_id, quantity, unit_price }, ... ]
     * payment_method, customer_phone, discount_amount, notes
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,M-Pesa,TigoPesa,Airtel Money,Bank,Credit Card',
            'customer_phone' => 'nullable|string|max:20',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $sale = DB::transaction(function () use ($validated) {
                // Lock the product rows we're about to sell so two cashiers
                // ringing up the same item at once can't oversell stock.
                $productIds = collect($validated['items'])->pluck('product_id')->unique();
                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $subtotal = 0;
                $lines = [];

                foreach ($validated['items'] as $line) {
                    $product = $products->get($line['product_id']);

                    if (!$product) {
                        throw new \RuntimeException("Bidhaa haipatikani (ID: {$line['product_id']}).");
                    }

                    if ($product->stock_quantity < $line['quantity']) {
                        throw new \RuntimeException("Stock haitoshi kwa bidhaa: {$product->name}. Iliyopo: {$product->stock_quantity}.");
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

                $discount = $validated['discount_amount'] ?? 0;
                $totalPrice = $subtotal - $discount;

                $sale = Sale::create([
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'total_price' => $totalPrice,
                    'payment_method' => $validated['payment_method'],
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'notes' => $validated['notes'] ?? null,
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

                // Update customer's running total, kama mteja ana namba ya simu
                if (!empty($validated['customer_phone'])) {
                    $customer = Customer::firstOrCreate(
                        ['phone' => $validated['customer_phone']],
                        ['name' => $validated['customer_phone']]
                    );
                    $customer->increment('total_spent', $totalPrice);
                    $customer->update(['last_purchase_date' => now()]);
                }

                return $sale;
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['items' => $e->getMessage()])->withInput();
        }

        return redirect()->route('web.sales.show', $sale)->with('success', 'Mauzo yamehifadhiwa! Risiti iko tayari.');
    }

    /**
     * Display the specified resource (works as the invoice/receipt view too).
     */
    public function show(Sale $sale)
    {
        $sale->load(['items.product.category', 'customer']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Editing line items after stock has already moved is risky (it can
     * silently corrupt stock counts), so editing here is limited to the
     * order-level fields. To change what was sold, void this sale and
     * record a new one.
     */
    public function edit(Sale $sale)
    {
        $sale->load('items.product');
        return view('sales.edit', compact('sale'));
    }

    /**
     * Update the specified resource in storage (order-level fields only).
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:Cash,M-Pesa,TigoPesa,Airtel Money,Bank,Credit Card',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $sale->update($validated);

        return redirect()->route('web.sales.index')->with('success', 'Sale updated successfully!');
    }

    /**
     * Remove the specified resource from storage (void the sale and restore stock).
     */
    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->load('items');

            foreach ($sale->items as $item) {
                Product::whereKey($item->product_id)->lockForUpdate()->increment('stock_quantity', $item->quantity);
            }

            $sale->items()->delete();
            $sale->delete();
        });

        return redirect()->route('web.sales.index')->with('success', 'Sale voided and stock restored.');
    }

    /**
     * Search products for autocomplete (used by the cart UI).
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('stock_quantity', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('product_code', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'product_code', 'selling_price', 'stock_quantity', 'unit')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'text' => "{$product->name} - {$product->product_code} (Stock: {$product->stock_quantity} {$product->unit})",
                    'unit' => $product->unit,
                    'price' => $product->selling_price,
                    'stock' => $product->stock_quantity,
                ];
            });

        return response()->json($products);
    }
}
