<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Data\User\User;
use App\Models\ClassElement;
use App\Models\Course;
use App\Models\Group;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GroupController
{
    public function create(Request $request) {
        $user = Auth::user();

        if (!$user->is_teacher)
            return response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId
        ] = $request->route()->parameters();

        try {
            $newGroup = $request->validate([
                "title" => ['required', 'string', 'max:255'],
                "maxMembers" => ['required', 'integer', 'min:2'],
                "themes" => [
                    'present',
                    'array',
                    Rule::when(
                        fn () => \count($request->input('themes')) != 0,
                        ['min:2'],
                    )
                ],
                "themes.*" => ['required', 'string', 'distinct']
            ]);

            if (!Course::where('id', $courseId)->exists())
                throw ValidationException::withMessages([ "course" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->where('id', $subjectId)
                ->exists()
            )
                throw ValidationException::withMessages([ "subject" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->where('id', $classroomId)
                ->exists()
            )
                throw ValidationException::withMessages([ "classroom" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->teachers()
                ->where('id', $user->id)
                ->exists()
            )
                throw ValidationException::withMessages([ "teacher" => "validation.is_teacher" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->classes()
                ->where('id', $classId)
                ->exists()
            )
                throw ValidationException::withMessages([ "class" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group = Group::create([
            "title" => $newGroup["title"],
            "max_members" => $newGroup["maxMembers"]
        ]);

        $group->themes()->createMany(array_map(
            fn ($t) => [ "theme" => $t ],
            $newGroup["themes"]
        ));

        ClassElement::create([
            "name" => $newGroup["title"],
            "type" => "group",
            "class_id" => $classId,
            "element_id" => $group->id
        ]);
    }

    public function edit(Request $request) {
        $user = Auth::user();

        if (!$user->is_teacher)
            return response()->json([ "error" => "O usuário não tem permissão para modificar um grupo"], 403);

        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        try {
            $editGroup = $request->validate([
                "title" => ['required', 'string', 'max:255'],
                "maxMembers" => ['required', 'integer', 'min:2'],
                "themes" => [
                    'present',
                    'array',
                    Rule::when(
                        fn () => \count($request->input('themes')) != 0,
                        ['min:2'],
                    )
                ],
                "themes.*" => ['required', 'string', 'distinct']
            ]);

            if (!Course::where('id', $courseId)->exists())
                throw ValidationException::withMessages([ "course" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->where('id', $subjectId)
                ->exists()
            )
                throw ValidationException::withMessages([ "subject" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->where('id', $classroomId)
                ->exists()
            )
                throw ValidationException::withMessages([ "classroom" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->teachers()
                ->where('id', $user->id)
                ->exists()
            )
                throw ValidationException::withMessages([ "teacher" => "validation.is_teacher" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->classes()
                ->where('id', $classId)
                ->exists()
            )
                throw ValidationException::withMessages([ "class" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->classes()
                ->find($classId)
                ->elements()
                ->where([
                    [ 'element_id', $classElementId ],
                    [ 'type', 'group' ]
                ])
                ->exists()
            )
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group = Course::find($courseId)
            ->subjects()
            ->find($subjectId)
            ->classrooms()
            ->find($classroomId)
            ->classes()
            ->find($classId)
            ->elements()
            ->where([
                [ 'element_id', $classElementId ],
                [ 'type', 'group' ]
            ])
            ->first()
            ->group;

        $group->title = $editGroup["title"];
        $group->max_members = $editGroup["maxMembers"];

        $group->themes()->delete();

        $group->themes()->createMany(array_map(
            fn ($t) => [ "theme" => $t ],
            $editGroup["themes"]
        ));

        $group->save();
    }

    public function createGroup(Request $request) {
        $user = Auth::user();

        if (!$user->is_teacher)
            return response()->json([ "error" => "O usuário não tem permissão para modificar um grupo"], 403);

        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        try {
            $newGroup = $request->validate([
                "name" => ['required', 'string', 'max:255'],
                "members" => ['required', 'array'],
                "members.*" => ['required', 'integer', 'exists:users,id']
            ]);

            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            $group = $classroom
                ?->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    [ 'element_id', $classElementId ],
                    [ 'type', 'group' ]
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            foreach ($newGroup["members"] as $value)
                if (!$classroom->students()->where('id', $value)->exists())
                    throw ValidationException::withMessages([ "members" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->syncWithoutDetaching(
            array_reduce(
                $newGroup["members"],
                function ($carry, $item) use ($newGroup) {
                    $carry[$item] = [
                        "group_name" => $newGroup["name"],
                        "status" => "inGroup"
                    ];

                    return $carry;
                },
                []
            )
        );
    }

    public function editGroup(Request $request) {
        $user = Auth::user();

        if (!$user->is_teacher)
            return response()->json([ "error" => "O usuário não tem permissão para modificar um grupo"], 403);

        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId,
            "groupName" => $groupName
        ] = $request->route()->parameters();

        try {
            $editGroup = $request->validate([
                "name" => ['required', 'string', 'max:255'],
                "members" => ['required', 'array'],
                "members.*" => ['required', 'integer', 'exists:users,id']
            ]);

            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            $group = $classroom
                ?->classes()
                ->find($classId)
                ?->elements()
                ->where([
                    [ 'element_id', $classElementId ],
                    [ 'type', 'group' ]
                ])
                ->first()
                ?->group;

            if ($group == null)
                throw ValidationException::withMessages([ "group" => "validation.exists" ]);

            foreach ($editGroup["members"] as $value)
                if (!$classroom->students()->where('id', $value)->exists())
                    throw ValidationException::withMessages([ "members" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $group->members()->where('group_name', $groupName)->delete();

        $group->members()->attach($editGroup["members"], [
            "group_name" => $editGroup["name"],
            "status" => "inGroup"
        ]);

        return [
            "name" => $editGroup["name"],
            "members" => array_map(
                fn ($m) => User::fromUserModelIncomplete($m),
                $group->members()->where('group_name', $groupName)->get()->all()
            )
        ];
    }
}