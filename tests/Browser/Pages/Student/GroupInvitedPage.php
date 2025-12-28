<?php

namespace Tests\Browser\Pages\Student;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class GroupInvitedPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    private int $classId;

    private int $elementId;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId,
        int $classId,
        int $elementId
    ) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->classId = $classId;
        $this->elementId = $elementId;

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
        return "/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/class/$this->classId/group/$this->elementId";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@accept')
            ->assertPresent('@decline');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@accept' => '[dusk="accept"]',
            '@decline' => '[dusk="decline"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@decline';
    }

    public function accept() {
        $this->browser->click('@accept');

        sleep(2); //wait for the async function to be handled
    }

    public function decline() {
        $this->browser->click('@decline');

        sleep(2); //wait for the async function to be handled
    }
}
