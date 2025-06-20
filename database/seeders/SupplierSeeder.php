<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Tanzania Electronics Ltd',
                'phone' => '+255 744 123 456',
                'email' => 'info@tanzaniaelectronics.co.tz',
                'address' => 'Dar es Salaam, Tanzania'
            ],
            [
                'name' => 'Fashion House Tanzania',
                'phone' => '+255 755 789 012',
                'email' => 'sales@fashionhouse.co.tz',
                'address' => 'Arusha, Tanzania'
            ],
            [
                'name' => 'Fresh Foods Tanzania',
                'phone' => '+255 756 345 678',
                'email' => 'orders@freshfoods.co.tz',
                'address' => 'Mwanza, Tanzania'
            ],
            [
                'name' => 'Home & Garden Supplies',
                'phone' => '+255 757 901 234',
                'email' => 'contact@homegarden.co.tz',
                'address' => 'Dodoma, Tanzania'
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
