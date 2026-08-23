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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2); // bei ya kuuzia wakati wa mauzo (bei aliyolipa mteja kwa kipande kimoja)
            $table->decimal('purchase_price', 10, 2); // "snapshot" ya purchase_price wakati huo, kwa COGS sahihi hata bei ya baadaye ikibadilika
            $table->decimal('line_total', 10, 2); // quantity * unit_price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
