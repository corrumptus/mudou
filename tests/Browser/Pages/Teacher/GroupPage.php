<?php

namespace Tests\Browser\Pages\Teacher;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class GroupPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    private int $classId;

    private int $groupId;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId,
        int $classId,
        int $groupId
    ) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->classId = $classId;
        $this->groupId = $groupId;

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
        return "/teacher/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/class/$this->classId/group/$this->groupId";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@edit')
            ->assertPresent('@to-groups')
            ->assertPresent('@to-users')
            ->assertPresent('@add');
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
            '@to-groups' => '[dusk="to-groups"]',
            '@to-users' => '[dusk="to-users"]',
            '@add' => '[dusk="add"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@add';
    }

    public function toEdit(string $waitUrl) {
        $this->browser->click('@edit');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function toAdd(string $waitUrl) {
        $this->browser->click('@add');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function toEditGroup(string $groupName, string $waitUrl) {
        $this->browser->click("[dusk='edit-$groupName']");

        $this->browser->waitForLocationDecoded($waitUrl);
    }
}