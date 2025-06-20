<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample sales
        $sales = [
            [
                'product_id' => 1,
                'quantity_sold' => 2,
                'sale_price' => 900000,
                'total_price' => 1800000,
                'payment_method' => 'M-Pesa',
                'customer_phone' => '+255 744 111 111',
                'sale_time' => now()->subDays(2)->setTime(10, 30),
            ],
            [
                'product_id' => 2,
                'quantity_sold' => 5,
                'sale_price' => 25000,
                'total_price' => 125000,
                'payment_method' => 'Cash',
                'customer_phone' => '+255 755 222 222',
                'sale_time' => now()->subDays(1)->setTime(14, 15),
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
