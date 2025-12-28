<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Group;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\EditGroupGroupPage;
use Tests\DuskTestCase;

class EditGroupGroupTest extends DuskTestCase
{
    private string $token;

    private Group $group;

    private array $students;

    private array $selectedIds;

    private string $groupName;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_teacher" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->students = User::factory()->count(8)->create()->all();

        Classroom::factory()
            ->hasAttached($this->students, relationship: 'students')
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
            "title" => $groupElement->name,
            "max_members" => 4
        ]);

        $ids = array_map(fn ($u) => $u->id, $this->students);

        $this->selectedIds = fake()->randomElements($ids, $this->group->max_members);

        $this->groupName = str_replace("'", "", fake()->name());

        $this->group->members()->attach(
            $this->selectedIds,
            [
                'group_name' => $this->groupName,
                'status' => 'inGroup'
            ]
        );
    }

    public function testShouldEditAGroupCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new EditGroupGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id,
                $this->groupName
            );

            $page->fillAndSubmit([ "name" => str_replace("'", "", fake()->name()), "members" => array_map(fn ($u) => $u->id, $this->students) ]);

            $message = $page->getAlertMessage();

            assert($message == "Grupo atualizado com sucesso");
        });
    }
}