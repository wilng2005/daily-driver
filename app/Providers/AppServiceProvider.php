<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('ExecutingAIResponses', function (object $job) {
            return Limit::perMinute(3)->by($job->telegramChat->tg_chat_id);
        });

        RateLimiter::for('EncourageUser', function (object $job) {
            return Limit::perHour(100)->by($job->telegramChat->tg_chat_id);
        });
    }
}
