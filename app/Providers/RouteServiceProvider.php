<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        Log::info('RouteServiceProvider boot method called');
        $this->routes(function () {
            Log::info('Loading API routes from routes/api.php');
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Log::info('Loading web routes from routes/web.php');
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}