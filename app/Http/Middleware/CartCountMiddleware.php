<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartCountMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get or create session ID
        if (!session()->has('cart_session_id')) {
            session(['cart_session_id' => Str::uuid()]);
        }
        
        $sessionId = session('cart_session_id');
        
        // Get cart count
        $cart = Cart::where('session_id', $sessionId)->first();
        $cartCount = 0;
        
        if ($cart) {
            $cartCount = $cart->items()->sum('quantity');
            session(['cart_count' => $cartCount]);
        }
        
        // Make cart count available to all views
        view()->share('cartCount', $cartCount);
        
        return $next($request);
    }
}