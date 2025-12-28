<?php

namespace Tests\Browser\Pages\Student;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class GroupInGroupPage extends Page
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
            ->assertPresent('@leave');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@leave' => '[dusk="leave"]',
        ];
    }

    public function getLastRenderElement(): string {
        return '@leave';
    }

    public function leave() {
        $this->browser->click('@leave');

        sleep(2); //wait for the async function to be handled
    }

    public function cancel(int $id) {
        $this->browser->click("@cancel-$id");

        sleep(2); //wait for the async function to be handled
    }

    public function accept(int $id) {
        $this->browser->click("@accept-$id");

        sleep(2); //wait for the async function to be handled
    }

    public function decline(int $id) {
        $this->browser->click("@decline-$id");

        sleep(2); //wait for the async function to be handled
    }
}
