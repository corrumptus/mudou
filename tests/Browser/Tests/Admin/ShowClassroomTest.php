<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Classroom;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\ShowClassroomPage;
use Tests\DuskTestCase;

class ShowClassroomTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->classroom = Classroom::factory()
            ->hasAttached(
                User::factory(),
                [],
                'teachers'
            )
            ->hasAttached(
                User::factory(),
                [],
                'monitors'
            )
            ->hasAttached(
                User::factory(),
                [],
                'students'
            )
            ->create();
    }

    public function testShouldRenderCreateUserPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new ShowClassroomPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id
            );

            $page->clickEdit();

            $browser->assertPathIsDecoded("/course/{$this->classroom->courseSubject->course->name}/subject/{$this->classroom->courseSubject->name}/classroom/{$this->classroom->id}/edit");
        });
    }
}
