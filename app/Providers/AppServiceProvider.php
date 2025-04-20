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
            view()->composer('layouts.app', function ($view) {
                if (Auth::check()) {
                    $cartItems = Auth::user()->cart()->with('book')->get();
                    $totalPrice = $cartItems->sum(function($item) {
                        return $item->book->price * $item->quantity;
                    });
                    
                    $view->with([
                        'cartItems' => $cartItems,
                        'totalPrice' => $totalPrice,
                        'cartCount' => $cartItems->count()
                    ]);
                } else {
                    $view->with([
                        'cartItems' => collect(),
                        'totalPrice' => 0,
                        'cartCount' => 0
                    ]);
                }
            });
    }
}
