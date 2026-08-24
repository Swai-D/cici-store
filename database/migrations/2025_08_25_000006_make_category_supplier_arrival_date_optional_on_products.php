<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Category/Supplier zilikuwa required kabisa kwenye bidhaa, jambo
     * lililokuwa linazuia duka jipya lisiloweza kuongeza bidhaa ya kwanza
     * kabla ya kuunda category na supplier (hatua za ziada zisizo za lazima).
     * arrival_date pia inakuwa optional kwa sababu sasa Purchases/Stock-In
     * module ndiyo chanzo halisi cha historia ya tarehe za manunuzi.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['supplier_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->change();
            $table->foreignId('supplier_id')->nullable()->change();
            $table->date('arrival_date')->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['supplier_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable(false)->change();
            $table->foreignId('supplier_id')->nullable(false)->change();
            $table->date('arrival_date')->nullable(false)->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }
};
