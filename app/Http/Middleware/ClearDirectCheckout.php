<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearDirectCheckout
{
    public function handle(Request $request, Closure $next): Response
    {
        // Niat "Beli sekarang" hanya berlaku selama pelanggan masih di alur checkout langsung.
        // Pindah ke halaman lain dianggap membatalkannya.
        if (
            $request->session()->has('direct_checkout')
            && ! $request->routeIs('checkout.direct', 'checkout.direct.store')
        ) {
            $request->session()->forget('direct_checkout');
        }

        return $next($request);
    }
}