<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\HourlyStockCheckJob;

class Kernel extends ConsoleKernel
{
    
    protected function schedule(Schedule $schedule)
    {
        // Run the stock check every hour
        $schedule->command('stock:check')->hourly();
        $schedule->command('report:daily-sales')->dailyAt('20:00');
    }

   
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
