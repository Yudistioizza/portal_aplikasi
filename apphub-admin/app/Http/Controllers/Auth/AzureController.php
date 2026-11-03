<?php

// Login SSO Microsoft (Azure AD) dinonaktifkan.
// Untuk mengaktifkan kembali: buka komentar controller ini, route di routes/web.php,
// listener di EventServiceProvider, dan tombol di resources/views/auth/login.blade.php.

/*
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AzureController extends Controller
{
    public function redirectToAzure()
    {
        return Socialite::driver('azure')->redirect();
    }

    public function handleAzureCallback()
    {
        $azureUser = Socialite::driver('azure')->user();

        $user = User::where('email', $azureUser->getEmail())->first();

        // Hanya user bertipe SSO yang boleh masuk lewat Azure
        if ($user && $user->auth_type === 'sso') {
            Auth::login($user);
            return redirect()->intended('/home');
        }

        return redirect('/login')->withErrors([
            'email' => 'Akun tidak terdaftar atau metode login tidak sesuai.',
        ]);
    }
}
*/
