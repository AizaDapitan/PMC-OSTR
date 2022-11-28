<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\DeptuserTrans;
use App\Models\TransmittalItem;
use App\Models\Worksheet;
use DateTime;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //
        view()->composer(
            'layouts.app',
            function ($view) {
              
                $reason = "";
                $scheduledate = "";
                $scheduletime = "";

                $from = now()->setTime(0, 0, 0)->toDateTimeString();
                $to = now()->subDays(-5)->setTime(0, 0, 0)->toDateTimeString();
                $schedule =  Application::where('deleted_at', null)->whereBetween('scheduled_date', [$from, $to])->orderBy('scheduled_date', 'asc')->first();
                $datetime = new DateTime();
                $currentDateTime = new DateTime();
                if ($schedule) {
                    $datetime = $schedule['scheduled_date'] . ' ' . $schedule['scheduled_time'];
                    $datetime = new DateTime($datetime);
                    $currentDateTime = new DateTime();
                    if ($currentDateTime < $datetime) {

                        $reason = $schedule['reason'];
                        $scheduledate = $schedule['scheduled_date'];
                        $scheduletime = str_replace(':00.0000000', '', $schedule['scheduled_time']);
                    }
                }

                $view->with(
                    compact(
                        'reason',
                        'scheduledate',
                        'scheduletime',
                        'datetime',
                        'currentDateTime'
                    )
                );
            }
        );
    }
}
