<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\DeptuserTrans;
use App\Models\StockRequest;
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
                $unsaved = StockRequest::where([['isSaved', 0], ['active', 1], ['created_by', auth()->user()->username]])->count();
                $approval = StockRequest::where([['isSaved', 1], ['active', 1], ['dept', auth()->user()->dept],['status','submitted']])->count();
                $receiving = StockRequest::where([['isReceived' , null ], ['active', 1], ['status','fully Approved']])->count();
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
                        'currentDateTime',
                        'unsaved',
                        'approval',
                        'receiving'
                    )
                );
            }
        );
    }
}
