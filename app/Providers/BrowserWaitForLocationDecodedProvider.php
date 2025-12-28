<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class BrowserWaitForLocationDecodedProvider extends ServiceProvider
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
        Browser::macro('waitForLocationDecoded', function (string $path, int|null $seconds = null) {
            $encodedUrl = str_replace(
                '%2F',
                '/',
                rawurlencode($path)
            );

            try {
                $this->waitForLocation($encodedUrl, $seconds);
            } catch (\Throwable $th) {
                throw $th;
            }

            return $this;
        });
    }
}
