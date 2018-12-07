<?php

namespace App\Console;

use App\Console\Commands\MakeEndpoint;
use App\Core\Guid\GuidRepository;
use App\Jobs\CleanupInvalidTokens;
use App\Jobs\CleanupNonAssignedGuids;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        MakeEndpoint::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(
            $this->app->make(CleanupNonAssignedGuids::class)
        )
            ->daily()
            ->description('cleanup non assigned guids older than 10 days')
            ->name('cleanup_non_assigned_guids')
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->job(
            $this->app->make(CleanupInvalidTokens::class)
        )
            ->everyMinute()
            ->description('cleanup tokens where the expiration date has passed')
            ->name('cleanup_tokens_expiration_date_passed')
            ->withoutOverlapping()
            ->runInBackground();
    }
}
