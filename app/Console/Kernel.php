<?php

namespace App\Console;

use App\Console\Commands\Supplier\UpdateSupplierLoanInfo;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use SyncBrandPath;
use UpdateProductPrice;

/**
 * Class Kernel
 *
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        UpdateSupplierLoanInfo::class,
        //根据调价单同步更新商品价格
        UpdateProductPrice::class,
        SyncBrandPath::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('supplier:update-loan-info')
        ->dailyAt('0:00');
        $schedule->command('product:update-product-price')
            ->dailyAt('0:05');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
