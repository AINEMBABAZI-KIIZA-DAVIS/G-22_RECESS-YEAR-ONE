<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryAndProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample inventory levels data
        $inventoryLevels = [
            ['month' => 'January', 'level' => 100],
            ['month' => 'February', 'level' => 150],
            ['month' => 'March', 'level' => 200],
            ['month' => 'April', 'level' => 175],
            ['month' => 'May', 'level' => 225],
            ['month' => 'June', 'level' => 250],
        ];

        // Sample top products data
        $topProducts = [
            ['product_name' => 'Bread', 'units_sold' => 320],
            ['product_name' => 'Milk', 'units_sold' => 280],
            ['product_name' => 'Eggs', 'units_sold' => 250],
            ['product_name' => 'Butter', 'units_sold' => 180],
            ['product_name' => 'Cheese', 'units_sold' => 150],
        ];

        // Insert data into inventory_levels table
        DB::table('inventory_levels')->insert($inventoryLevels);

        // Insert data into top_products table
        DB::table('top_products')->insert($topProducts);
    }
}
