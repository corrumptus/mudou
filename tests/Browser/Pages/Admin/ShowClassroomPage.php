<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class ShowClassroomPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    public function __construct(Browser $browser, string $token, string $courseName, string $subjectName, int $classroomId) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;

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
        return "/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/show";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@edit')
            ->assertPresent('@infos');
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
            '@infos' => '[dusk="infos"]'
        ];
    }

    public function getLastRenderElement(): string
    {
        return '@infos';
    }

    public function clickEdit() {
        $this->browser->click('@edit');

        $this->browser->waitForLocationDecoded("/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/edit");
    }
}
