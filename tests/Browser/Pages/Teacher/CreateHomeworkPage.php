<?php

namespace Tests\Browser\Pages\Teacher;

use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class CreateHomeworkPage extends Page
{
    private string $courseName;
    
    private string $subjectName;
    
    private int $classroomId;
    
    private int $classId;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId,
        int $classId
    ) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->classId = $classId;

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
        return "/teacher/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/class/$this->classId/homework/new";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@title')
            ->assertPresent('@description')
            ->assertPresent('@begin-date')
            ->assertPresent('@begin-time')
            ->assertPresent('@close-date')
            ->assertPresent('@close-time')
            ->assertPresent('@can-accept-after-close')
            ->assertPresent('@has-group')
            ->assertNotPresent('@group')
            ->assertPresent('@is-text')
            ->assertNotPresent('@max-amount-caracteres')
            ->assertPresent('@is-file')
            ->assertPresent('@max-amount-files')
            ->assertPresent('@file-types')
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
            '@title' => '[dusk="title"]',
            '@description' => '[dusk="description"]',
            '@begin-date' => '[dusk="begin-date"]',
            '@begin-time' => '[dusk="begin-time"]',
            '@close-date' => '[dusk="close-date"]',
            '@close-time' => '[dusk="close-time"]',
            '@can-accept-after-close' => '[dusk="can-accept-after-close"]',
            '@has-group' => '[dusk="has-group"]',
            '@group' => '[dusk="group"]',
            '@is-text' => '[dusk="is-text"]',
            '@max-amount-caracteres' => '[dusk="max-amount-caracteres"]',
            '@is-file' => '[dusk="is-file"]',
            '@max-amount-files' => '[dusk="max-amount-files"]',
            '@file-types' => '[dusk="file-types"]',
            '@confirm' => '[dusk="confirm"]',
            '@cancel' => '[dusk="cancel"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@cancel';
    }

    public function fillAndSubmit(array $homework) {
        $this->browser->type('@title', $homework["title"]);
        $this->browser->type('@description', $homework["description"]);

        $this->browser->type('@begin-date', Carbon::parse($homework["begin_date_time"])->format('m-d-Y'));
        $this->browser->type('@begin-time', Carbon::parse($homework["begin_date_time"])->format('h'));
        $this->browser->type('@begin-time', Carbon::parse($homework["begin_date_time"])->format('i'));
        $this->browser->type('@begin-time', Carbon::parse($homework["begin_date_time"])->format('A'));

        $this->browser->type('@close-date', Carbon::parse($homework["close_date_time"])->format('m-d-Y'));
        $this->browser->type('@close-time', Carbon::parse($homework["close_date_time"])->format('h'));
        $this->browser->type('@close-time', Carbon::parse($homework["close_date_time"])->format('i'));
        $this->browser->type('@close-time', Carbon::parse($homework["close_date_time"])->format('A'));

        if (!$homework["can_accept_after_close"])
            $this->browser->click('@can-accept-after-close');

        if ($homework["is_text"]) {
            $this->browser->click('@is-text');

            $this->browser->type('@max-amount-caracteres', $homework["max_amount_caracteres"]);
        }

        if (!$homework["is_file"])
            $this->browser->click('@is-file');

        if ($homework["is_file"]) {
            $this->browser->type('@max-amount-files', $homework["max_amount_files"]);

            $this->browser->type('@file-types', $homework["file_types"]);
        }

        $this->browser->click('@confirm');

        sleep(2); //wait for the async function to be handled
    }
}
