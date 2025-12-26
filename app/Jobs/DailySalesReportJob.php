<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailySalesReportMail;
use Illuminate\Foundation\Bus\Dispatchable;

class DailySalesReportJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, Dispatchable;

    public function handle()
    {
        Log::info('DailySalesReportJob started at ' . now());

        $orders = DB::table('orders')
            ->whereDate('created_at', now()->subDay()->toDateString())
            ->whereIn('status', [0, 1])
            ->get();
        
        if ($orders->isEmpty()) {
            Log::info('No orders for yesterday.');
            return;
        }

        $orderIds = $orders->pluck('id');

        $orderNumbers = $orders->pluck('order_number', 'id');
        $items = DB::table('order_items')
            ->whereIn('order_id', $orderIds)
            ->get();

        $csvFile = 'daily_sales_report_' . now()->subDay()->format('Y_m_d') . '.csv';
        $filePath = 'private/' . $csvFile;

        $handle = fopen(storage_path('app/' . $filePath), 'w');

        fputcsv($handle, [
            'Order Number', 'Product ID', 'Product Name', 'Quantity', 'Price', 'Total', 'Order Created At'
        ]);

        foreach ($items as $item) {
            fputcsv($handle, [
                $orderNumbers[$item->order_id] ?? $item->order_id,
                $item->product_id,
                $item->product_name_snapshot,
                $item->quantity,
                $item->price_snapshot,
                $item->total,
                $item->created_at,

            ]);
        }

        fclose($handle);

        Log::info("CSV generated at storage/app/{$filePath}");
        $adminEmail = 'admin@example.com';
        Mail::to($adminEmail)->send(new DailySalesReportMail($filePath));
        DB::table('email_logs')->insert([
            'type' => 'daily_sales_report',
            'recipient' => $adminEmail,
            'payload' => json_encode(['file' => $filePath]),
            'sent_at' => now(),
        ]);

        Log::info('DailySalesReportJob finished at ' . now());
    }
}
