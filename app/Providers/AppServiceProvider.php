<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        // ✅ Load API routes directly (no /api prefix)
        Route::middleware('api')
            ->group(base_path('routes/api.php'));

        // ✅ Load web routes normally
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
