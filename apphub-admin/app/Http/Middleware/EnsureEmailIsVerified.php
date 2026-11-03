<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Cek apakah email belum diverifikasi
        if (is_null($user->email_verified_at)) {
            return redirect()->route('verification.notice')->with('error', 'Harap verifikasi alamat email Anda terlebih dahulu.');
        }

        return $next($request);
    }
}