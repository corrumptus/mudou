<?php

namespace Tests\Browser\Pages\General;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class NewDiscussionCommentPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    private int $discussionId;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId,
        int $discussionId
    ) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->discussionId = $discussionId;

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
        return "/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/forun/$this->discussionId";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@write-comment')
            ->assertPresent('@send-comment');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@write-comment' => '[dusk="write-comment"]',
            '@send-comment' => '[dusk="send-comment"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@send-comment';
    }

    public function fillAndSubmit(array $comment) {
        $this->browser->type('@write-comment', $comment["comment"]);

        $this->browser->click('@send-comment');

        sleep(2); //wait for the async function to be handled
    }
}
