<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing products
        $products = Product::all();

        if ($products->count() > 0) {
            $firstProduct = $products->first();
            $secondProduct = $products->count() > 1 ? $products->get(1) : $firstProduct;

            // Sample sale #1: single item
            $sale1 = Sale::create([
                'subtotal' => 1800000,
                'discount_amount' => 0,
                'total_price' => 1800000,
                'payment_method' => 'M-Pesa',
                'customer_phone' => '+255 744 111 111',
                'sale_time' => now()->subDays(2)->setTime(10, 30),
            ]);

            SaleItem::create([
                'sale_id' => $sale1->id,
                'product_id' => $firstProduct->id,
                'quantity' => 2,
                'unit_price' => 900000,
                'purchase_price' => $firstProduct->purchase_price,
                'line_total' => 1800000,
            ]);

            // Sample sale #2: multi-item, to demonstrate the cart-based flow
            $sale2 = Sale::create([
                'subtotal' => 125000,
                'discount_amount' => 5000,
                'total_price' => 120000,
                'payment_method' => 'Cash',
                'customer_phone' => '+255 755 222 222',
                'sale_time' => now()->subDays(1)->setTime(14, 15),
            ]);

            SaleItem::create([
                'sale_id' => $sale2->id,
                'product_id' => $secondProduct->id,
                'quantity' => 5,
                'unit_price' => 25000,
                'purchase_price' => $secondProduct->purchase_price,
                'line_total' => 125000,
            ]);
        }

        // Create sample expenses
        $expenses = [
            [
                'category' => 'Rent',
                'amount' => 500000,
                'description' => 'Monthly store rent',
                'expense_date' => now()->subDays(5),
            ],
            [
                'category' => 'Marketing',
                'amount' => 100000,
                'description' => 'Facebook and Instagram ads',
                'expense_date' => now()->subDays(3),
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
}
