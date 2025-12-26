<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStockSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = DB::table('products')->pluck('id');

        $stocks = [];

        foreach ($productIds as $productId) {
            $quantity = rand(0, 200);

            $stocks[] = [
                'product_id' => $productId,
                'quantity_available' => $quantity,
                'low_stock_threshold' => rand(5, 20),
                'added_by' => 1,
                'updated_at' => now(),
            ];
        }

        DB::table('product_stocks')->insert($stocks);
    }
}
