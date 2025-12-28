<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Group;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\CreateGroupGroupPage;
use Tests\DuskTestCase;

class CreateGroupGroupTest extends DuskTestCase
{
    private string $token;

    private Group $group;

    private array $students;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

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
            "title" => $groupElement->name
        ]);
    }

    public function testShouldCreateANewGroupCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new CreateGroupGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $selectedStudents = fake()->randomElements($this->students, $this->group->max_members);

            $page->fillAndSubmit([ "name" => str_replace("'", "", fake()->name()), "members" => array_map(fn ($s) => $s->id, $selectedStudents) ]);

            $message = $page->getAlertMessage();

            assert($message == "Grupo criado com sucesso");
        });
    }
}