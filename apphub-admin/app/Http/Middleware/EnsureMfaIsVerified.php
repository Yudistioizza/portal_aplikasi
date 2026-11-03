<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureMfaIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Jika user tidak login atau belum setup MFA, lanjut tanpa verifikasi
        if (!$user || !$user->mfa_secret) {
            return $next($request);
        }

        // Jika sudah MFA verified di session, lanjut
        if (session('mfa_verified')) {
            return $next($request);
        }

        // Periksa trusted device cookie
        $trustedToken = $request->cookie('trusted_device_' . $user->id);
        $expectedToken = hash_hmac('sha256', $user->id . '|' . $request->ip() . '|' . $user->email, config('app.key'));

        // Jika cookie trusted valid, set session MFA dan lanjut
        if ($trustedToken && hash_equals($trustedToken, $expectedToken)) {
            session(['mfa_verified' => true]);
            return $next($request);
        }

        // Jika tidak login, tidak ada session MFA, dan tidak trusted device, redirect OTP page
        return redirect()->route('mfa.verify'); // Pastikan route ini sesuai dengan route OTP kamu
    }
}