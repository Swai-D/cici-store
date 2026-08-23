<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases (stock-in history).
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['items.product', 'supplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        $purchases = $query->latest('purchase_date')->paginate(10)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for recording a new stock-in.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchases.create', compact('suppliers'));
    }

    /**
     * Store a newly recorded purchase (stock-in) and increment product stock.
     *
     * Expected payload:
     * items: [ { product_id, quantity, unit_cost }, ... ]
     * supplier_id, purchase_date, notes
     * update_selling_price (optional bool per item not supported yet — purchase_price always updates)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $purchase = DB::transaction(function () use ($validated) {
            $productIds = collect($validated['items'])->pluck('product_id')->unique();
            $products = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $totalCost = 0;
            $lines = [];

            foreach ($validated['items'] as $line) {
                $product = $products->get($line['product_id']);
                $lineTotal = $line['quantity'] * $line['unit_cost'];
                $totalCost += $lineTotal;

                $lines[] = [
                    'product' => $product,
                    'quantity' => $line['quantity'],
                    'unit_cost' => $line['unit_cost'],
                    'line_total' => $lineTotal,
                ];
            }

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'purchase_date' => $validated['purchase_date'],
                'total_cost' => $totalCost,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($lines as $line) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $line['product']->id,
                    'quantity' => $line['quantity'],
                    'unit_cost' => $line['unit_cost'],
                    'line_total' => $line['line_total'],
                ]);

                // Ongeza stock, na update purchase_price ya bidhaa kuwa bei
                // ya hivi karibuni tuliyonunua (kwa COGS sahihi ya mauzo yajayo).
                $line['product']->increment('stock_quantity', $line['quantity']);
                $line['product']->update(['purchase_price' => $line['unit_cost']]);
            }

            return $purchase;
        });

        return redirect()->route('web.purchases.show', $purchase)->with('success', 'Stock imeongezwa kikamilifu!');
    }

    /**
     * Display the specified purchase.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['items.product', 'supplier']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Void a purchase and reverse the stock it added.
     * Guards against leaving stock negative (e.g. some of that stock was
     * already sold), which would mean the numbers can no longer reconcile.
     */
    public function destroy(Purchase $purchase)
    {
        try {
            DB::transaction(function () use ($purchase) {
                $purchase->load('items.product');

                foreach ($purchase->items as $item) {
                    $product = Product::whereKey($item->product_id)->lockForUpdate()->first();

                    if ($product && $product->stock_quantity < $item->quantity) {
                        throw new \RuntimeException(
                            "Haiwezekani ku-void: stock ya '{$product->name}' tayari imepungua chini ya kiasi kilichoongezwa na hii purchase (labda tayari imeuzwa)."
                        );
                    }

                    $product?->decrement('stock_quantity', $item->quantity);
                }

                $purchase->items()->delete();
                $purchase->delete();
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['purchase' => $e->getMessage()]);
        }

        return redirect()->route('web.purchases.index')->with('success', 'Purchase imeondolewa na stock imerudishwa.');
    }

    /**
     * Search products for autocomplete on the stock-in cart (mirrors sales
     * search but doesn't filter to stock > 0, since we're adding stock).
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('product_code', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'product_code', 'purchase_price', 'stock_quantity', 'unit')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'text' => "{$product->name} - {$product->product_code} (Stock ya sasa: {$product->stock_quantity} {$product->unit})",
                    'unit' => $product->unit,
                    'cost' => $product->purchase_price,
                ];
            });

        return response()->json($products);
    }
}
