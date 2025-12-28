<?php

namespace Tests\Browser\Tests\Student;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Group;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Student\GroupInGroupPage;
use Tests\Browser\Pages\Student\GroupInvitedPage;
use Tests\Browser\Pages\Student\GroupNoGroupPage;
use Tests\Browser\Pages\Student\GroupRequestingGroupPage;
use Tests\DuskTestCase;

class GroupTest extends DuskTestCase
{
    private User $user;

    private string $token;

    private Group $group;

    private array $studentIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->token = JWTHelper::encode($this->user);

        $classroom = Classroom::factory()
            ->hasAttached([ $this->user, ...User::factory()->count(8)->create()->all() ], relationship: 'students')
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

        $this->studentIds = $classroom->students()
            ->whereNot('id', $this->user->id)
            ->get()
            ->map(fn ($s) => $s->id)
            ->all();
    }

    public function testShouldCreateAGroupCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $this->group->members()->attach(
                User::factory()->count($this->group->max_members)->create()->all(),
                [
                    "group_name" => str_replace("'", "", fake()->name()),
                    "status" => 'inGroup'
                ]
            );

            $this->group->members()->attach(
                User::factory()->count($this->group->max_members-1)->create()->all(),
                [
                    "group_name" => str_replace("'", "", fake()->name()),
                    "status" => 'inGroup'
                ]
            );

            $page = new GroupNoGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $randomMembers = fake()->randomElements($this->studentIds, $this->group->max_members);

            $page->fillAndSubmitWithoutTheme([ "name" => str_replace("'", "", fake()->name()), "members" => $randomMembers ]);

            $message = $page->getAlertMessage();

            assert($message === "Grupo criado com sucesso");
        });
    }

    public function testShouldCancelInviteCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $name = str_replace("'", "", fake()->name());

            $this->group->members()->attach(
                [ $this->user->id ],
                [
                    "group_name" => $name,
                    "status" => 'inGroup'
                ]
            );

            $this->group->members()->attach(
                fake()->randomElements($this->studentIds, $this->group->max_members-1),
                [
                    "group_name" => $name,
                    "status" => 'invited'
                ]
            );

            $page = new GroupInGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $id = fake()->randomElement(
                $this->group->members()
                    ->wherePivot('group_name', $name)
                    ->wherePivot('status', 'invited')
                    ->get()
                    ->all()
            )->id;

            $page->cancel($id);

            $message = $page->getAlertMessage();

            assert($message === "Convite desfeito com sucesso");
        });
    }

    public function testShouldRequestEnterAGroupCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $this->group->members()->attach(
                User::factory()->count($this->group->max_members-1)->create()->all(),
                [
                    "group_name" => str_replace("'", "", fake()->name()),
                    "status" => 'inGroup'
                ]
            );

            $this->group->members()->attach(
                User::factory()->count($this->group->max_members-1)->create()->all(),
                [
                    "group_name" => str_replace("'", "", fake()->name()),
                    "status" => 'inGroup'
                ]
            );

            $page = new GroupNoGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $groupName = fake()->randomElement($this->group->members()->withPivot(['group_name'])->get()->map(fn ($m) => $m->pivot->group_name)->all());

            $page->requestEnterGroup($groupName);

            $message = $page->getAlertMessage();

            assert($message === "Pedido enviado com sucesso");
        });
    }

    public function testShouldCancelARequestToEnterAGroupCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $name = str_replace("'", "", fake()->name());

            $this->group->members()->attach($this->user->id, [
                'group_name' => $name,
                'status' => 'requesting'
            ]);

            $this->group->members()->attach(fake()->randomElements($this->studentIds, $this->group->max_members-1), [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $page = new GroupRequestingGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $page->cancel();

            $message = $page->getAlertMessage();

            assert($message === "Pedido cancelado com sucesso");
        });
    }

    public function testShouldEnterAGroupCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $name = str_replace("'", "", fake()->name());

            $this->group->members()->attach($this->user->id, [
                'group_name' => $name,
                'status' => 'invited'
            ]);

            $this->group->members()->attach(fake()->randomElements($this->studentIds, $this->group->max_members-1), [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $page = new GroupInvitedPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $page->accept();

            $message = $page->getAlertMessage();

            assert($message === "Convite aceito com sucesso");
        });
    }

    public function testShouldNotEnterAGroupCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $name = str_replace("'", "", fake()->name());

            $this->group->members()->attach($this->user->id, [
                'group_name' => $name,
                'status' => 'invited'
            ]);

            $this->group->members()->attach(fake()->randomElements($this->studentIds, $this->group->max_members-1), [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $page = new GroupInvitedPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $page->decline();

            $message = $page->getAlertMessage();

            assert($message === "Convite recusado com sucesso");
        });
    }

    public function testShouldAcceptEnterGroupRequestCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $name = str_replace("'", "", fake()->name());

            $this->group->members()->attach($this->user->id, [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $this->group->members()->attach(fake()->randomElements($this->studentIds, $this->group->max_members-1), [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $id = fake()->randomElement($this->studentIds);
            
            $this->group->members()->attach($id, [
                'group_name' => $name,
                'status' => 'requesting'
            ]);

            $page = new GroupInGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $page->accept($id);

            $message = $page->getAlertMessage();

            assert($message === "Pedido aceito com sucesso");
        });
    }

    public function testShouldDeclineEnterGroupRequestCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $name = str_replace("'", "", fake()->name());

            $this->group->members()->attach($this->user->id, [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $this->group->members()->attach(fake()->randomElements($this->studentIds, $this->group->max_members-1), [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $id = fake()->randomElement($this->studentIds);

            $this->group->members()->attach($id, [
                'group_name' => $name,
                'status' => 'requesting'
            ]);

            $page = new GroupInGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $page->decline($id);

            $message = $page->getAlertMessage();

            assert($message === "Pedido recusado com sucesso");
        });
    }

    public function testShouldLeaveGroupCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $name = str_replace("'", "", fake()->name());

            $this->group->members()->attach($this->user->id, [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $this->group->members()->attach(fake()->randomElements($this->studentIds, $this->group->max_members-1), [
                'group_name' => $name,
                'status' => 'inGroup'
            ]);

            $page = new GroupInGroupPage(
                $browser,
                $this->token,
                $this->group->classElement->class->classroom->courseSubject->course->name,
                $this->group->classElement->class->classroom->courseSubject->name,
                $this->group->classElement->class->classroom->id,
                $this->group->classElement->class->id,
                $this->group->id
            );

            $page->leave();

            $message = $page->getAlertMessage();

            assert($message === "Grupo deixado com sucesso");
        });
    }
}