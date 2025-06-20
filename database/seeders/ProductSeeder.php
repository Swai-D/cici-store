<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'product_code' => 'ELEC001',
                'name' => 'Samsung Smartphone',
                'description' => 'Latest Samsung smartphone with advanced features',
                'category_id' => 1,
                'supplier_id' => 1,
                'purchase_price' => 800000,
                'selling_price' => 950000,
                'discount_price' => 900000,
                'stock_quantity' => 15,
                'arrival_date' => now()->subDays(5),
            ],
            [
                'product_code' => 'CLOTH001',
                'name' => 'Traditional Kanga',
                'description' => 'Beautiful traditional Tanzanian kanga',
                'category_id' => 2,
                'supplier_id' => 2,
                'purchase_price' => 15000,
                'selling_price' => 25000,
                'stock_quantity' => 50,
                'arrival_date' => now()->subDays(3),
            ],
            [
                'product_code' => 'FOOD001',
                'name' => 'Organic Coffee Beans',
                'description' => 'Premium organic coffee beans from Kilimanjaro',
                'category_id' => 3,
                'supplier_id' => 3,
                'purchase_price' => 8000,
                'selling_price' => 12000,
                'stock_quantity' => 8,
                'arrival_date' => now()->subDays(1),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
