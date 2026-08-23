<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Kipimo cha bidhaa: kipande, kg, lita, dazani, pakiti, mita, n.k.
            $table->string('unit')->default('kipande')->after('description');
        });

        // stock_quantity kutoka integer kwenda decimal, ili bidhaa zinazouzwa
        // kwa kg/lita ziweze kuwa na stock ya sehemu (mfano 2.5 kg).
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('stock_quantity', 10, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_quantity')->default(0)->change();
            $table->dropColumn('unit');
        });
    }
};
