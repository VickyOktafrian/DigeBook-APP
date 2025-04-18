<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * It is also set as the URL generator's root namespace.
     */
    // Jika kamu ingin menggunakan namespace default untuk controller, bisa diaktifkan
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();
    
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
