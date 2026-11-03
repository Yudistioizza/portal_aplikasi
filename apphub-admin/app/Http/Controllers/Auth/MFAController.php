<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use PragmaRX\Google2FAQRCode\Google2FA;

class MFAController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    /**
     * Show MFA Setup Page (Generate QR and Secret)
     */
    public function showSetup()
    {
        $user = Auth::user();

        // Generate MFA Secret if not exist
        if (!$user->mfa_secret) {
            $user->mfa_secret = $this->google2fa->generateSecretKey();
            $user->save();
        }

        // Generate QR Code inline
        $QR_Image = $this->google2fa->getQRCodeInline(
            config('app.name'), // Nama aplikasi
            $user->email,       // Email user
            $user->mfa_secret   // Secret user
        );

        return view('auth.mfa-setup', compact('QR_Image', 'user'));
    }

    /**
     * Show MFA Verification Page
     */
    public function showVerify()
    {
        return view('auth.mfa-verify');
    }

    /**
 * Verify MFA Token (OTP) and Handle Trusted Device for 30 Days
 */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();
        $otp = $request->input('otp');

        // Verifikasi OTP
        $isValidOtp = $this->google2fa->verifyKey($user->mfa_secret, $otp);

        if ($isValidOtp) {
            // ✅ Set session MFA verified
            session(['mfa_verified' => true]);

            // Cek apakah remember_me disimpan saat login
            $rememberMe = session('remember_me', false); // default false jika tidak ada

            // Buat trusted device cookie HANYA jika remember me dicentang
            if ($rememberMe) {
                $trustedToken = hash_hmac('sha256', $user->id . '|' . $request->ip() . '|' . $user->email, config('app.key'));
                Cookie::queue('trusted_device_' . $user->id, $trustedToken, 60 * 24 * 30); // 30 hari (dalam menit)
            }

            // Hapus session remember_me karena tidak dibutuhkan lagi
            session()->forget('remember_me');

            return redirect()->intended('/home')->with('success', 'Verifikasi MFA berhasil!');
        } else {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid. Silakan coba lagi.']);
        }
    }
}