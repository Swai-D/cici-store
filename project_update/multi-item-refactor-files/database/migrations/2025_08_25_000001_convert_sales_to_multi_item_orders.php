<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) Ongeza columns mpya za "order header" kwenye sales
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->nullable()->after('transaction_code');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal');
            $table->text('notes')->nullable()->after('customer_phone');
        });

        // 2) Hamisha data ya sasa (kila sale ya bidhaa moja) kuwa sale_item moja,
        //    ili tusipoteze mauzo ya mtihani aliyoyafanya mteja tayari.
        if (Schema::hasColumn('sales', 'product_id')) {
            $existingSales = DB::table('sales')->get();

            foreach ($existingSales as $sale) {
                if (empty($sale->product_id)) {
                    continue;
                }

                $product = DB::table('products')->find($sale->product_id);

                DB::table('sale_items')->insert([
                    'sale_id' => $sale->id,
                    'product_id' => $sale->product_id,
                    'quantity' => $sale->quantity_sold,
                    'unit_price' => $sale->sale_price,
                    // Hatuna historia ya purchase_price wakati huo, kwa hiyo tunatumia
                    // purchase_price ya sasa ya bidhaa kama makadirio bora tuliyonayo.
                    'purchase_price' => $product->purchase_price ?? 0,
                    'line_total' => $sale->total_price,
                    'created_at' => $sale->created_at,
                    'updated_at' => $sale->updated_at,
                ]);

                DB::table('sales')->where('id', $sale->id)->update([
                    'subtotal' => $sale->total_price,
                ]);
            }
        }

        // 3) Ondoa columns za zamani za "single item" kwenye sales
        //    (sasa zinaishi kwenye sale_items)
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'quantity_sold', 'sale_price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('transaction_code')->constrained()->onDelete('cascade');
            $table->integer('quantity_sold')->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
        });

        // Rudisha data ya kwanza ya kila sale kutoka sale_items (best-effort, si kamili)
        $items = DB::table('sale_items')->orderBy('sale_id')->get()->groupBy('sale_id');
        foreach ($items as $saleId => $lines) {
            $first = $lines->first();
            DB::table('sales')->where('id', $saleId)->update([
                'product_id' => $first->product_id,
                'quantity_sold' => $first->quantity,
                'sale_price' => $first->unit_price,
            ]);
        }

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount_amount', 'notes']);
        });
    }
};
