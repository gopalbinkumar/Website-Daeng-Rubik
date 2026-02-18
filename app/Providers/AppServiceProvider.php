<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\EventRegistration;
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

            $pendingTransactionCount = Transaction::where('status', 'pending')->count();
            $pendingParticipantCount = EventRegistration::where('status', 'pending')->count();

            $view->with([
                'pendingTransactionCount' => $pendingTransactionCount,
                'pendingParticipantCount' => $pendingParticipantCount,
            ]);
        });


    }

}
