<?php
// app/Http/Middleware/CustomerMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        // OPSIONAL: Jika punya multi-role system
        // if (Auth::user()->role !== 'customer') {
        //     abort(403, 'Akses ditolak. Halaman ini khusus untuk customer.');
        // }

        return $next($request);
    }
}