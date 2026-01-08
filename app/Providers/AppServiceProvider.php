<?php

namespace App\Providers;

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
        // Kita atur standar password aplikasi jadi minimal 4 karakter
        Password::defaults(function () {
            return Password::min(4); 
            
            // Kalau mau lebih kompleks (opsional):
            // return Password::min(4)->letters()->numbers();
        });
    }
}
