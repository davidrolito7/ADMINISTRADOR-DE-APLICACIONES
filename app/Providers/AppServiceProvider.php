<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

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
        Filament::serving(function (): void {
            FilamentColor::register([
                'primary' => session('adm_database_mode', 'production') === 'testing'
                    ? Color::Blue
                    : Color::Amber,
            ]);
        });
    }
}
