<?php

namespace App\Jobs;

use App\Models\ProductStock;
use App\Jobs\LowStockNotificationJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HourlyStockCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('HourlyStockCheckJob started at ' . now());

        $productStocks = ProductStock::all();

        foreach ($productStocks as $stock) {
            $soldQty = DB::table('order_items')
                ->where('product_id', $stock->product_id)
                ->sum('quantity');

            $remainingStock = $stock->quantity_available - $soldQty;

            if ($remainingStock <= $stock->low_stock_threshold) {
                $product = $stock->product;
                if ($product) {
                    Log::info("Low stock detected for Product ID: {$product->id}, remaining stock: {$remainingStock}");

                    LowStockNotificationJob::dispatch($product);
                     DB::table('email_logs')->insert([
                        'type'      => 'low_stock',
                        'recipient' => $product->notification_email ?? 'admin',
                        'payload'   => json_encode([
                            'product_id'      => $product->id,
                            'product_name'    => $product->name,
                            'remaining_stock' => $remainingStock,
                            'threshold'       => $stock->low_stock_threshold,
                        ]),
                        'user_id'  => null,
                        'added_by'=> null,
                        'sent_at' => now(),
                    ]);
                }
            }
        }

        Log::info('HourlyStockCheckJob finished at ' . now());
    }
}
