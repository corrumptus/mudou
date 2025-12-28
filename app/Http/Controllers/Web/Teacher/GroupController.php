<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Data\Group\Group as GroupDTO;
use App\Data\User\User;
use App\Models\Course;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController
{
    public function create(Request $request)
    {
        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('classroomId');

        $user = Auth::user();

        return Inertia::render(
            'routes/Teacher/ManageGroup',
            ClassroomController::pageProps(
                $courseName,
                $subjectName,
                $classroomId,
                $user
            )
        );
    }

    public function show(Request $request)
    {
        [
            "courseName" => $courseName,
            "subjectName" => $subjectName,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        $user = Auth::user();

        $classroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        if ($classroom == null)
            return Inertia::render(
                'routes/Teacher/ManageGroup', [
                    ...ClassroomController::pageProps(
                        $courseName,
                        $subjectName,
                        $classroomId,
                        $user
                    ),
                    "group" => null
                ]
            );

        $group = $classroom
            ->classes()
            ->find($classId)
            ?->elements()
            ->where([
                ['element_id', $classElementId],
                ['type', 'group']
            ])
            ->first()
            ?->group;

        return Inertia::render(
            'routes/Teacher/Group', [
                ...ClassroomController::pageProps(
                    $courseName,
                    $subjectName,
                    $classroomId,
                    $user
                ),
                "group" => $classroom->teachers()->where('id', $user->id)->exists() ?
                    GroupDTO::forTeacher($group)
                    :
                    null
            ]
        );
    }

    public function edit(Request $request)
    {
        [
            "courseName" => $courseName,
            "subjectName" => $subjectName,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        $user = Auth::user();

        $classroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        if ($classroom == null)
            return Inertia::render(
                'routes/Teacher/ManageGroup', [
                    ...ClassroomController::pageProps(
                        $courseName,
                        $subjectName,
                        $classroomId,
                        $user
                    ),
                    "group" => null
                ]
            );

        $group = $classroom
            ->classes()
            ->find($classId)
            ?->elements()
            ->where([
                ['element_id', $classElementId],
                ['type', 'group']
            ])
            ->first()
            ?->group;

        return Inertia::render(
            'routes/Teacher/ManageGroup', [
                ...ClassroomController::pageProps(
                    $courseName,
                    $subjectName,
                    $classroomId,
                    $user
                ),
                "group" => $classroom->teachers()->where('id', $user->id)->exists() ?
                    GroupDTO::forTeacherEdit($group)
                    :
                    null
            ]
        );
    }

    public function newGroup(Request $request)
    {
        [
            "courseName" => $courseName,
            "subjectName" => $subjectName,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        $user = Auth::user();

        $classroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        if ($classroom == null)
            return Inertia::render(
                'routes/Teacher/ManageGroupGroup', [
                    ...ClassroomController::pageProps(
                        $courseName,
                        $subjectName,
                        $classroomId,
                        $user
                    )
                ]
            );

        $group = $classroom
            ->classes()
            ->find($classId)
            ?->elements()
            ->where([
                ['element_id', $classElementId],
                ['type', 'group']
            ])
            ->first()
            ?->group;

        return Inertia::render(
            'routes/Teacher/ManageGroupGroup', [
                ...ClassroomController::pageProps(
                    $courseName,
                    $subjectName,
                    $classroomId,
                    $user
                ),
                "groupMaker" => $group != null && $classroom->teachers()->where('id', $user->id)->exists() ?
                    [
                        "id" => $group->id,
                        "users" => array_map(
                            fn ($u) => User::fromUserModelIncomplete($u),
                            $classroom->students()
                                ->whereNotIn(
                                    'id',
                                    $group->members()
                                        ->wherePivot('status', '!=', 'requesting')
                                        ->select('id')
                                )
                                ->get()
                                ->all()
                        ),
                        "classId" => $classId
                    ]
                    :
                    null
            ]
        );
    }

    public function editGroup(Request $request)
    {
        [
            "courseName" => $courseName,
            "subjectName" => $subjectName,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId,
            "groupName" => $groupName
        ] = $request->route()->parameters();

        $user = Auth::user();

        $classroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        if ($classroom == null)
            return Inertia::render(
                'routes/Teacher/ManageGroupGroup', [
                    ...ClassroomController::pageProps(
                        $courseName,
                        $subjectName,
                        $classroomId,
                        $user
                    )
                ]
            );

        $group = $classroom
            ->classes()
            ->find($classId)
            ?->elements()
            ->where([
                ['element_id', $classElementId],
                ['type', 'group']
            ])
            ->first()
            ?->group;

        return Inertia::render(
            'routes/Teacher/ManageGroupGroup', [
                ...ClassroomController::pageProps(
                    $courseName,
                    $subjectName,
                    $classroomId,
                    $user
                ),
                ...(
                    $group != null
                    &&
                    $classroom->teachers()->where('id', $user->id)->exists()
                    &&
                    $group->members()->where('group_name', $groupName)->exists() ?
                    [
                        "groupMaker" =>
                            [
                                "id" => $group->id,
                                "users" => array_map(
                                    fn ($u) => User::fromUserModelIncomplete($u),
                                    $classroom->students()
                                        ->whereNotIn(
                                            'id',
                                            $group->members()
                                                ->wherePivot('status', '!=', 'requesting')
                                                ->wherePivot('group_name', '!=', $groupName)
                                                ->select('id')
                                        )
                                        ->get()
                                        ->all()
                                ),
                                "classId" => $classId
                            ],
                        "group" => [
                            "name" => $groupName,
                            "members" => array_map(
                                fn ($m) => User::fromUserModelIncomplete($m),
                                $group->members()->where('group_name', $groupName)->get()->all()
                            )
                        ]
                    ]
                    :
                    []
                )
                
            ]
        );
    }
}