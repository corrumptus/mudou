<?php

namespace Tests\Browser\Pages\Teacher;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class AnnouncementPage extends Page
{
    private string $course;

    private string $courseSubject;

    private string $classroomId;

    public function __construct(
        Browser $browser,
        string $token,
        string $course,
        string $courseSubject,
        string $classroomId
    ) {
        $this->course = $course;
        $this->courseSubject = $courseSubject;
        $this->classroomId = $classroomId;
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
        return "/course/$this->course/subject/$this->courseSubject/classroom/$this->classroomId/announcements";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@add')
            ->assertPresent('@announcement');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@add' => '[dusk="add"]',
            '@announcement' => '[dusk="announcement"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@announcement';
    }

    public function toNew() {
        $this->browser->click('@add');

        $this->browser->waitForLocationDecoded("/teacher/course/$this->course/subject/$this->courseSubject/classroom/$this->classroomId/announcements/new");
    }
}
