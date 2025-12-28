<?php

namespace Tests\Browser\Pages\Student;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class HomeworkPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    private int $classId;

    private int $elementId;

    private bool $isText;

    private bool $isFile;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId,
        int $classId,
        int $elementId,
        bool $isText,
        bool $isFile
    ) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->classId = $classId;
        $this->elementId = $elementId;
        $this->isText = $isText;
        $this->isFile = $isFile;

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
        return "/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/class/$this->classId/homework/$this->elementId";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@send');

            if ($this->isText)
                $browser->assertPresent('@text');

            if ($this->isFile)
                $browser->assertPresent('@file');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@text' => '[dusk="text"]',
            '@file' => '[dusk="file"]',
            '@send' => '[dusk="send"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@send';
    }

    public function fillAndSubmit(string $text/*, $files*/) {
        if ($this->isText)
            $this->browser->type('@text', $text);

        /*
        if ($this->isFile)
            $this->browser->('@file', $files);
        */

        $this->browser->click('@send');

        sleep(2); // wait for the async function to be handled
    }
}
