<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Group;
use App\Models\GroupTheme;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\EditGroupPage;
use Tests\DuskTestCase;

class EditGroupTest extends DuskTestCase
{
    private string $token;

    private Group $group;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_teacher" => true ]);

        $this->token = JWTHelper::encode($user);

        $students = User::factory()->count(8)->create()->all();

        Classroom::factory()
            ->hasAttached($students, relationship: 'students')
            ->hasAttached($user, relationship: 'teachers')
            ->has(
                DayClass::factory()
                    ->has(
                        ClassElement::factory()
                            ->state(fn ($s) => [ "type" => "group" ]),
                        'elements'
                    ),
                'classes'
            )
            ->create();

        $groupElement = ClassElement::where('type', 'group')->first();

        $this->group = Group::factory()->create([
            "id" => $groupElement->element_id,
            "title" => $groupElement->name
        ]);
    }

    public function testShouldEditGroupWithoutThemesCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new EditGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $newGroup = Group::factory()->raw();

            $page->fillAndSubmitWithoutThemes([ ...$newGroup, "themes" => [] ]);

            $message = $page->getAlertMessage();

            assert($message == "Grupo atualizado com sucesso");
        });
    }

    public function testShouldEditGroupWithThemesCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new EditGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $newGroup = Group::factory()->raw();

            $themes = GroupTheme::factory()
                ->count(rand(2, 4))
                ->make()
                ->map(fn ($t) => $t->theme)
                ->all();

            $page->fillAndSubmitWithThemes([ ...$newGroup, "themes" => $themes ]);

            $message = $page->getAlertMessage();

            assert($message == "Grupo atualizado com sucesso");
        });
    }
}