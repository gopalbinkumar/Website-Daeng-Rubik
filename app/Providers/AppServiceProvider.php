<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

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
         View::composer('*', function ($view) {
        $count = 0;

        $cart = Cart::where('status', 'active')
            ->when(Auth::check(), function ($q) {
                $q->where('user_id', Auth::id());
            }, function ($q) {
                $q->where('session_token', session('cart_token'));
            })
            ->with('items')
            ->first();

        if ($cart) {
            $count = $cart->items->sum('quantity');
        }

        $view->with('cartItemCount', $count);
    }); 
    }
}
