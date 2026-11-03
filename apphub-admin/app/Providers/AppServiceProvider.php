<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Password::defaults(function () {
            return Password::min(8)       // Minimal 8 karakter
                ->mixedCase()             // Kombinasi huruf besar & kecil
                ->numbers()               // Harus ada angka
                ->symbols()               // Harus ada simbol
                ->uncompromised();        // Cek data breach (password yang pernah bocor)
        });
    }
}
