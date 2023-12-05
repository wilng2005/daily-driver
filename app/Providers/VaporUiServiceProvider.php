<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class VaporUiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->gate();
    }

    /**
     * Register the Vapor UI gate.
     *
     * This gate determines who can access Vapor UI in non-local environments.
     */
    protected function gate()
    {
        Gate::define('viewVaporUI', function ($user = null) {
            return true;
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
