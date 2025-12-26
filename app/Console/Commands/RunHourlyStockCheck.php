<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\HourlyStockCheckJob;

class RunHourlyStockCheck extends Command
{
    
    protected $signature = 'stock:check';

    
    protected $description = 'Run the hourly stock check job';

    
    public function handle()
    {
        $this->info('Dispatching HourlyStockCheckJob...');

        HourlyStockCheckJob::dispatch();

        $this->info('HourlyStockCheckJob dispatched successfully!');
    }
}
