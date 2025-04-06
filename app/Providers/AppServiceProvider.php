<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Observers\ScheduleObserver;
use App\Models\Schedule;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Route::aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);
        Schedule::observe(ScheduleObserver::class);
    }
}
