<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
{
    $this->routes(function () {

        // ✅ API routes - with or without /api prefix
        Route::middleware('api')
            ->prefix('api') // ← keep this if you want /api in URL
            ->group(base_path('routes/api.php'));

        // ✅ Web routes
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    });
}
}
