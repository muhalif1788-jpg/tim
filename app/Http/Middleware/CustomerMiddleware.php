<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        if (Auth::user()->role !== 'customer') {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk customer.');
        }

        return $next($request);
    }
}