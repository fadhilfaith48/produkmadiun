<?php
// =====================================================
// FILE: app/Http/Middleware/RoleMiddleware.php
// Middleware untuk cek role user
// =====================================================
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (Auth::user()->role !== $role) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}
