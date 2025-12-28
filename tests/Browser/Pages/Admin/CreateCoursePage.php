<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class CreateCoursePage extends Page
{
    public function __construct(Browser $browser, string $token) {
        $this->browser = $browser;

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
        return '/course/new';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIs($this->url())
            ->assertPresent('@name')
            ->assertPresent('@code')
            ->assertPresent('@amountSemesters')
            ->assertPresent('@confirm')
            ->assertPresent('@cancel');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@name' => '[dusk="name"]',
            '@code' => '[dusk="code"]',
            '@amountSemesters' => '[dusk="amountSemesters"]',
            '@confirm' => '[dusk="confirm"]',
            '@cancel' => '[dusk="cancel"]',
        ];
    }

    public function getLastRenderElement(): string
    {
        return '@cancel';
    }

    public function fillAndSubmit(array $user) {
        $this->browser->type('@name', $user['name']);
        $this->browser->type('@code', $user['code']);
        $this->browser->type('@amountSemesters', $user['amount_semesters']);

        $this->browser->click('@confirm');

        sleep(2); //wait the async function to be handled
    }
}
