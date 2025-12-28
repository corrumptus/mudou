<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class ShowUserPage extends Page
{
    private string $email;

    public function __construct(Browser $browser, string $token, string $email) {
        $this->browser = $browser;
        $this->email = $email;

        $browser
            ->loginCookie($token)
            ->visit($this->url())
            ->waitFor($this->getLastRenderElement());

        $this->assert($browser);
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return "/user/$this->email";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIs($this->url())
            ->assertPresent('@edit')
            ->assertPresent('@infos')
            ->assertPresent('@navigation');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@edit' => '[dusk="edit"]',
            '@infos' => '[dusk="infos"]',
            '@navigation' => '[dusk="navigation"]'
        ];
    }

    public function getLastRenderElement(): string
    {
        return '@navigation';
    }

    public function clickEdit() {
        $this->browser->click('@edit');

        $this->browser->waitForLocation("/user/$this->email/edit");
    }
}
