<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class BrowserAssertPathIsDecodedURLProvider extends ServiceProvider
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
        Browser::macro('assertPathIsDecoded', function (string $path) {
            $encodedUrl = str_replace(
                '%2F',
                '/',
                rawurlencode($path)
            );

            $this->assertPathIs($encodedUrl);

            return $this;
        });
    }
}
