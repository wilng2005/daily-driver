<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\TelegramChatProvider::class, \App\Services\EloquentTelegramChatProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Make all published insights available to the partials.bottom-section view
        \Illuminate\Support\Facades\View::composer('partials.bottom-section', function ($view) {
            $insights = \App\Models\Insight::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->get();
            $view->with('insights', $insights);
        });
        RateLimiter::for('ExecutingAIResponses', function (object $job) {
            return Limit::perMinute(3)->by($job->telegramChat->tg_chat_id);
        });

        RateLimiter::for('EncourageUser', function (object $job) {
            return Limit::perHour(100)->by($job->telegramChat->tg_chat_id);
        });
    }
}
