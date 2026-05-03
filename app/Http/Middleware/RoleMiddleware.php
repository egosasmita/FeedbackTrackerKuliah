<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // 3. Cek apakah role user ada di dalam daftar $roles yang diizinkan
        // Catatan: Ganti 'id_role' menjadi 'role' jika di DB kamu isinya teks (admin/dosen)
        if (in_array($user->id_role, $roles)) {
            return $next($request);
        }

        // 4. Jika tidak cocok, tampilkan error 403
        abort(403, 'Anda tidak memiliki hak akses untuk masuk ke halaman ini.');
    }
}