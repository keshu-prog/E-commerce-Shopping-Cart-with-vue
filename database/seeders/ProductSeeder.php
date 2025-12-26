<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [];

        for ($i = 1; $i <= 50; $i++) {
            $products[] = [
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'name' => 'Product ' . $i,
                'description' => 'Description for product ' . $i,
                'price' => rand(100, 5000) / 100,
                // 'images' => json_encode([
                //     'product_' . $i . '_1.jpg',
                //     'product_' . $i . '_2.jpg'
                // ]),
                'status' => 1,
                'added_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}
