<?php

namespace Tests\Browser\Pages\General;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class LoginPage extends Page
{
    private bool $waitOtherLocation;

    public function __construct(Browser $browser, bool $waitOtherLocation) {
        $this->browser = $browser;
        $this->waitOtherLocation = $waitOtherLocation;

        $browser->visit($this->url())
            ->waitFor($this->getLastRenderElement());

        $this->assert($browser);
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertPresent('@email')
            ->assertPresent('@password')
            ->assertPresent('@eye.open')
            ->assertPresent('@eye.closed')
            ->assertPresent('@submit')
            ->assertNotPresent('@error');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@email' => '[dusk="email"]',
            '@password' => '[dusk="password"]',
            '@eye.closed' => '[dusk="eye.closed"]',
            '@eye.open' => '[dusk="eye.open"]',
            '@submit' => '[dusk="submit"]',
            '@error' => '[dusk="error"]',
        ];
    }

    public function getLastRenderElement(): string {
        return '@submit';
    }

    public function fillAndSubmit(string $email, string $password) {
        $this->browser
            ->type('@email', $email)
            ->type('@password', $password)
            ->click('@submit');

            if ($this->waitOtherLocation)
                $this->browser->waitForLocation('/course-subject');
            else
                sleep(2); //wait the async submit function
    }

    public function getErrorMessage() {
        $error = $this->browser->element('@error');

        return $error == null ? "" : $error->getText();
    }
}
