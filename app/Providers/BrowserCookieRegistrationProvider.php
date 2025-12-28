<?php

namespace App\Providers;

use App\Http\Middleware\Web\WebAuthenticationService;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class BrowserCookieRegistrationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Browser::macro('loginCookie', function (string $token) {
            $this->visit('/')->waitFor('div#app');

            $this->plainCookie(
                WebAuthenticationService::$COOKIE_NAME,
                $token
            );

            return $this;
        });
    }
}
