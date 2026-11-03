<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override login method to handle "remember me" functionality.
     */
    public function login(Request $request)
    {
        // Validasi form login
        $this->validateLogin($request);

        // Ambil credentials (email & password)
        $credentials = $request->only($this->username(), 'password');

        // Ambil status checkbox remember me
        $remember = $request->filled('remember'); // True jika dicentang

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            // Jika berhasil login
            $request->session()->regenerate(); // Regenerate session untuk keamanan
            session()->flash('success', 'Anda berhasil masuk!'); // Flash message

            // Simpan remember me ke session sementara untuk MFA
            session(['remember_me' => $remember]);

            // Redirect ke MFA jika user perlu MFA
            return redirect()->route('mfa.verify'); // contoh jika pakai MFA
        }

        // Jika gagal login, kembali dengan error
        return back()->withErrors([
            $this->username() => 'Email atau password salah.',
        ])->withInput($request->only($this->username(), 'remember')); // Kembalikan input email & remember
    }


    /**
     * Redirect after login with message.
     */
    protected function redirectTo()
    {
        // Bisa custom route di sini jika mau beda-beda per role, dsb.
        return $this->redirectTo;
    }
}