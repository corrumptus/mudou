<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class EditCourseSubjectPage extends Page
{
    private string $courseName;

    private string $subjectName;

    public function __construct(Browser $browser, string $token, string $courseName, string $subjectName) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;

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
        return "/course/$this->courseName/subject/$this->subjectName/edit";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@name')
            ->assertPresent('@code')
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
            '@confirm' => '[dusk="confirm"]',
            '@cancel' => '[dusk="cancel"]',
        ];
    }

    public function getLastRenderElement(): string
    {
        return '@cancel';
    }

    public function fillAndSubmit(array $courseSubject) {
        $this->browser->type('@name', $courseSubject['name']);
        $this->browser->type('@code', $courseSubject['code']);

        $this->browser->click('@confirm');

        sleep(2); //wait the async function to be handled
    }
}
