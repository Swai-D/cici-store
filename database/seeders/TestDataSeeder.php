<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create additional categories
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Clothing', 'description' => 'Traditional and modern clothing'],
            ['name' => 'Food & Beverages', 'description' => 'Food items and drinks'],
            ['name' => 'Home & Garden', 'description' => 'Home improvement and garden supplies'],
            ['name' => 'Beauty & Health', 'description' => 'Beauty products and health items'],
            ['name' => 'Books & Stationery', 'description' => 'Books, papers, and office supplies'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        // Create additional suppliers
        $suppliers = [
            [
                'name' => 'Tech Solutions Tanzania',
                'phone' => '+255 744 111 111',
                'email' => 'sales@techsolutions.co.tz',
                'address' => 'Dar es Salaam, Tanzania'
            ],
            [
                'name' => 'Kilimanjaro Textiles',
                'phone' => '+255 755 222 222',
                'email' => 'info@kilimanjarotextiles.co.tz',
                'address' => 'Arusha, Tanzania'
            ],
            [
                'name' => 'Zanzibar Spices Co',
                'phone' => '+255 756 333 333',
                'email' => 'orders@zanzibarspices.co.tz',
                'address' => 'Zanzibar, Tanzania'
            ],
            [
                'name' => 'Mwanza Fisheries',
                'phone' => '+255 757 444 444',
                'email' => 'contact@mwanzafisheries.co.tz',
                'address' => 'Mwanza, Tanzania'
            ],
            [
                'name' => 'Dodoma Hardware',
                'phone' => '+255 758 555 555',
                'email' => 'sales@dodomahardware.co.tz',
                'address' => 'Dodoma, Tanzania'
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['name' => $supplier['name']], $supplier);
        }

        // Create additional products
        $products = [
            [
                'product_code' => 'PHONE001',
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with advanced camera features',
                'category_id' => 1,
                'supplier_id' => 1,
                'purchase_price' => 2500000,
                'selling_price' => 2800000,
                'discount_price' => 2700000,
                'stock_quantity' => 8,
                'arrival_date' => now()->subDays(10),
            ],
            [
                'product_code' => 'PHONE002',
                'name' => 'Samsung Galaxy S24',
                'description' => 'Premium Android smartphone',
                'category_id' => 1,
                'supplier_id' => 1,
                'purchase_price' => 1800000,
                'selling_price' => 2100000,
                'discount_price' => 2000000,
                'stock_quantity' => 12,
                'arrival_date' => now()->subDays(8),
            ],
            [
                'product_code' => 'KANGA001',
                'name' => 'Traditional Kanga Set',
                'description' => 'Beautiful traditional Tanzanian kanga (2 pieces)',
                'category_id' => 2,
                'supplier_id' => 2,
                'purchase_price' => 12000,
                'selling_price' => 20000,
                'discount_price' => 18000,
                'stock_quantity' => 25,
                'arrival_date' => now()->subDays(15),
            ],
            [
                'product_code' => 'KITENGE001',
                'name' => 'Kitenge Fabric',
                'description' => 'Colorful kitenge fabric (6 yards)',
                'category_id' => 2,
                'supplier_id' => 2,
                'purchase_price' => 8000,
                'selling_price' => 15000,
                'discount_price' => 13000,
                'stock_quantity' => 30,
                'arrival_date' => now()->subDays(12),
            ],
            [
                'product_code' => 'COFFEE001',
                'name' => 'Kilimanjaro Coffee Beans',
                'description' => 'Premium Arabica coffee beans from Kilimanjaro',
                'category_id' => 3,
                'supplier_id' => 3,
                'purchase_price' => 15000,
                'selling_price' => 25000,
                'discount_price' => 22000,
                'stock_quantity' => 20,
                'arrival_date' => now()->subDays(5),
            ],
            [
                'product_code' => 'SPICE001',
                'name' => 'Zanzibar Spice Mix',
                'description' => 'Authentic Zanzibar spice blend for cooking',
                'category_id' => 3,
                'supplier_id' => 3,
                'purchase_price' => 5000,
                'selling_price' => 8000,
                'discount_price' => 7000,
                'stock_quantity' => 40,
                'arrival_date' => now()->subDays(3),
            ],
            [
                'product_code' => 'FISH001',
                'name' => 'Fresh Tilapia Fish',
                'description' => 'Fresh tilapia fish from Lake Victoria',
                'category_id' => 3,
                'supplier_id' => 4,
                'purchase_price' => 3000,
                'selling_price' => 5000,
                'discount_price' => 4500,
                'stock_quantity' => 50,
                'arrival_date' => now()->subDays(1),
            ],
            [
                'product_code' => 'TOOL001',
                'name' => 'Hammer Set',
                'description' => 'Professional hammer set (3 pieces)',
                'category_id' => 4,
                'supplier_id' => 5,
                'purchase_price' => 25000,
                'selling_price' => 35000,
                'discount_price' => 32000,
                'stock_quantity' => 15,
                'arrival_date' => now()->subDays(7),
            ],
            [
                'product_code' => 'BEAUTY001',
                'name' => 'Natural Hair Oil',
                'description' => 'Natural hair oil with coconut and shea butter',
                'category_id' => 5,
                'supplier_id' => 2,
                'purchase_price' => 8000,
                'selling_price' => 12000,
                'discount_price' => 10000,
                'stock_quantity' => 35,
                'arrival_date' => now()->subDays(4),
            ],
            [
                'product_code' => 'BOOK001',
                'name' => 'Swahili Dictionary',
                'description' => 'Comprehensive Swahili-English dictionary',
                'category_id' => 6,
                'supplier_id' => 1,
                'purchase_price' => 15000,
                'selling_price' => 22000,
                'discount_price' => 20000,
                'stock_quantity' => 10,
                'arrival_date' => now()->subDays(6),
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['product_code' => $product['product_code']], $product);
        }

        // Create customers
        $customers = [
            [
                'name' => 'Amina Hassan',
                'phone' => '+255 744 123 456',
            ],
            [
                'name' => 'John Mwangi',
                'phone' => '+255 755 234 567',
            ],
            [
                'name' => 'Fatima Ali',
                'phone' => '+255 756 345 678',
            ],
            [
                'name' => 'David Kimani',
                'phone' => '+255 757 456 789',
            ],
            [
                'name' => 'Sarah Mwambene',
                'phone' => '+255 758 567 890',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['phone' => $customer['phone']], $customer);
        }

        // Get all products for sales
        $allProducts = Product::all();
        $customers = Customer::all();

        // Create sample sales
        $sales = [
            [
                'product_id' => $allProducts->where('product_code', 'PHONE001')->first()->id,
                'quantity_sold' => 1,
                'sale_price' => 2700000,
                'total_price' => 2700000,
                'payment_method' => 'M-Pesa',
                'customer_phone' => $customers->first()->phone,
                'sale_time' => now()->subDays(5)->setTime(9, 30),
            ],
            [
                'product_id' => $allProducts->where('product_code', 'KANGA001')->first()->id,
                'quantity_sold' => 3,
                'sale_price' => 18000,
                'total_price' => 54000,
                'payment_method' => 'Cash',
                'customer_phone' => $customers->get(1)->phone,
                'sale_time' => now()->subDays(4)->setTime(11, 15),
            ],
            [
                'product_id' => $allProducts->where('product_code', 'COFFEE001')->first()->id,
                'quantity_sold' => 2,
                'sale_price' => 22000,
                'total_price' => 44000,
                'payment_method' => 'Airtel Money',
                'customer_phone' => $customers->get(2)->phone,
                'sale_time' => now()->subDays(3)->setTime(14, 45),
            ],
            [
                'product_id' => $allProducts->where('product_code', 'FISH001')->first()->id,
                'quantity_sold' => 5,
                'sale_price' => 4500,
                'total_price' => 22500,
                'payment_method' => 'Cash',
                'customer_phone' => $customers->get(3)->phone,
                'sale_time' => now()->subDays(2)->setTime(16, 20),
            ],
            [
                'product_id' => $allProducts->where('product_code', 'BEAUTY001')->first()->id,
                'quantity_sold' => 2,
                'sale_price' => 10000,
                'total_price' => 20000,
                'payment_method' => 'M-Pesa',
                'customer_phone' => $customers->get(4)->phone,
                'sale_time' => now()->subDays(1)->setTime(10, 10),
            ],
            [
                'product_id' => $allProducts->where('product_code', 'TOOL001')->first()->id,
                'quantity_sold' => 1,
                'sale_price' => 32000,
                'total_price' => 32000,
                'payment_method' => 'Cash',
                'customer_phone' => $customers->first()->phone,
                'sale_time' => now()->subHours(6)->setTime(13, 30),
            ],
        ];

        foreach ($sales as $sale) {
            Sale::create($sale);
        }

        // Create sample expenses
        $expenses = [
            [
                'category' => 'Rent',
                'amount' => 500000,
                'description' => 'Monthly store rent payment',
                'expense_date' => now()->subDays(10),
            ],
            [
                'category' => 'Marketing',
                'amount' => 150000,
                'description' => 'Facebook and Instagram advertising',
                'expense_date' => now()->subDays(8),
            ],
            [
                'category' => 'Utilities',
                'amount' => 75000,
                'description' => 'Electricity and water bills',
                'expense_date' => now()->subDays(6),
            ],
            [
                'category' => 'Transportation',
                'amount' => 45000,
                'description' => 'Delivery van fuel and maintenance',
                'expense_date' => now()->subDays(4),
            ],
            [
                'category' => 'Staff',
                'amount' => 300000,
                'description' => 'Monthly staff salaries',
                'expense_date' => now()->subDays(2),
            ],
            [
                'category' => 'Insurance',
                'amount' => 80000,
                'description' => 'Business insurance premium',
                'expense_date' => now()->subDays(1),
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
} 