<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function map() : void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace('App\Http\Controllers\API\Auth')
            ->group(base_path('routes/auth.api.php'));

        Route::prefix('api')
            ->middleware('api')
            ->namespace('App\Http\Controllers\API\Business')
            ->group(base_path('routes/business.api.php'));
    }
}
