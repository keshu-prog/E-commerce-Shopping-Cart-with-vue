<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\DailySalesReportJob;

class SendDailySalesReport extends Command
{
    protected $signature = 'report:daily-sales';
    protected $description = 'Send daily sales report to admin';

    public function handle()
    {
        $this->info('Dispatching DailySalesReportJob...');
        DailySalesReportJob::dispatch();
        $this->info('DailySalesReportJob dispatched!');
    }
}
