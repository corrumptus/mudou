<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Group;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\GroupPage;
use Tests\DuskTestCase;

class GroupTest extends DuskTestCase
{
    private string $token;

    private Group $group;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

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

        $ids = array_map(fn ($u) => $u->id, $students);

        $selectedIds = fake()->randomElements($ids, 2*$this->group->max_members);

        $this->group->members()->attach(
            array_slice($selectedIds, 0, $this->group->max_members),
            [
                'group_name' => str_replace("'", "", fake()->name()),
                'status' => 'inGroup'
            ]
        );

        $this->group->members()->attach(
            array_slice($selectedIds, $this->group->max_members),
            [
                'group_name' => str_replace("'", "", fake()->name()),
                'status' => 'inGroup'
            ]
        );
    }

    public function testShouldRedirectToEditPageCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new GroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $url = $this->baseGroupUrl() . "/edit";

            $page->toEdit($url);
        });
    }

    public function testShouldRedirectToNewGroupPageCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new GroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $url = $this->baseGroupUrl() . "/new";

            $page->toAdd($url);
        });
    }

    public function testShouldRedirectToEditGroupPageCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = new GroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $groupName = fake()->randomElement($this->group->members()->withPivot([ 'group_name' ])->get()->all())->pivot->group_name;
            
            $url = $this->baseGroupUrl() . "/group/$groupName";

            $page->toEditGroup($groupName, $url);
        });
    }

    private function baseGroupUrl() {
        $courseName = $this->group->classElement->class->classroom->courseSubject->course->name;
        $subjectName = $this->group->classElement->class->classroom->courseSubject->name;
        $classroomId = $this->group->classElement->class->classroom->id;
        $classId = $this->group->classElement->class->id;
        $groupId = $this->group->id;

        return "/teacher/course/$courseName/subject/$subjectName/classroom/$classroomId/class/$classId/group/$groupId";
    }
}