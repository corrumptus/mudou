<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Group;
use App\Models\GroupTheme;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\CreateGroupPage;
use Tests\DuskTestCase;

class CreateGroupTest extends DuskTestCase
{
    private string $token;

    private DayClass $class;
    
    public function setUp(): void {
        parent::setUp();

        $user = User::factory()->create([ "is_teacher" => true ]);

        $this->token = JWTHelper::encode($user);

        $classroom = Classroom::factory()
            ->hasAttached($user, relationship: 'teachers')
            ->create();

        $this->class = DayClass::factory()->for($classroom)->create();
    }

    public function testShouldCreateGroupWithoutThemesCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new CreateGroupPage(
                $browser,
                $this->token,
                $this->class->classroom->courseSubject->course->name,
                $this->class->classroom->courseSubject->name,
                $this->class->classroom->id,
                $this->class->id
            );

            $groupAttributes = Group::factory()->raw();

            $page->fillAndSubmit([ ...$groupAttributes, "themes" => [] ]);

            $text = $page->getAlertMessage();

            assert($text == "Grupo criado com sucesso");
        });
    }

    public function testShouldCreateGroupWithThemesCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new CreateGroupPage(
                $browser,
                $this->token,
                $this->class->classroom->courseSubject->course->name,
                $this->class->classroom->courseSubject->name,
                $this->class->classroom->id,
                $this->class->id
            );

            $groupAttributes = Group::factory()->raw();

            $themes = GroupTheme::factory()
                ->count(rand(2, 8))
                ->make()
                ->map(fn ($t) => $t->theme)
                ->all();

            $page->fillAndSubmit([ ...$groupAttributes, "themes" => $themes ]);

            $text = $page->getAlertMessage();

            assert($text == "Grupo criado com sucesso");
        });
    }
}