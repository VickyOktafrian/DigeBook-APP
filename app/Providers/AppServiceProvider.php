<?php

namespace App\Providers;

use App\Models\Carts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        
    
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
        View::composer('*', function ($view) {
                $cartCount = 0;
        
                if (Auth::check()) {
                    $cartCount = Carts::where('user_id', Auth::id())->sum('quantity');
                }
        
                $view->with('cartCount', $cartCount);
            });
    }
}
