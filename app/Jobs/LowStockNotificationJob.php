<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LowStockNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; 

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle()
    {
        $adminEmail = 'admin@example.com';

        Mail::raw(
            "Product {$this->product->name} is low on stock. Current quantity: {$this->product->stock->quantity_available}",
            function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                        ->subject('Low Stock Alert');
            }
        );
    }
}
