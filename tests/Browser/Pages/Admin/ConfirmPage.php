<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class ConfirmPage extends Page
{
    private string $token;

    public function __construct(Browser $browser, string $token) {
        $this->browser = $browser;
        $this->token = $token;

        $browser->visit($this->url())
            ->waitFor($this->getLastRenderElement());

        $this->assert($browser);
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return "/first-access/$this->token";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@password' => '[dusk="password"]',
            '@confirm_password' => '[dusk="confirm_password"]',
            '@error' => '[dusk="error"]',
            '@submit' => '[dusk="submit"]'
        ];
    }

    public function getLastRenderElement(): string
    {
        return '@submit';
    }

    public function fillAndSubmit(string $password) {
        $this->browser->type('@password', $password);
        $this->browser->type('@confirm_password', $password);
        $this->browser->click('@submit');

        sleep(2); // wait the async function to be handled
    }
}
