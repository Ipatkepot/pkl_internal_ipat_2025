<?php
namespace App\Providers;

use App\Listeners\MergeCartListener;
use Illuminate\Auth\Events\Login;     // Import Facade Event
use Illuminate\Support\Facades\Event; // Import Event Login
use Illuminate\Support\ServiceProvider;
// Import Listener kamu

class EventServiceProvider extends ServiceProvider
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
        // Daftarkan Event & Listener di sini
        Event::listen(
            Login::class,
            MergeCartListener::class
        );
    }
}
