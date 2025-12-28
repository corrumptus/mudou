<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Course;
use App\Data\Classroom\Classroom as ClassroomDTO;
use App\Models\CourseSubject;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassroomController
{
    public function create()
    {
        $user = Auth::user();

        return Inertia::render('routes/Admin/ManageClassroom', [
            "courses" => array_map(
                fn ($c) => [
                    "id" => $c->id,
                    "name" => $c->name
                ],
                Course::get()->all()
            ),
            "courseSubjects" => array_reduce(
                CourseSubject::get()->all(),
                function ($carry, $item) {
                    $courseId = $item->course->id;

                    if (!\array_key_exists($courseId, $carry))
                        $carry[$courseId] = [];

                    $carry[$courseId][] = [
                        "id" => $item->id,
                        "name" => $item->name
                    ];

                    return $carry;
                },
                []
            ),
            "teachers" => array_map(
                fn ($u) => [
                    "id" => $u->id,
                    "name" => $u->name,
                    "img" => "/api/profilePic/$u->id"
                ],
                User::where('is_teacher', true)->get()->all()
            ),
            "monitors" => array_map(
                fn ($u) => [
                    "id" => $u->id,
                    "name" => $u->name,
                    "img" => "/api/profilePic/$u->id"
                ],
                User::where('is_monitor', true)->get()->all()
            ),
            "students" => array_map(
                fn ($u) => [
                    "id" => $u->id,
                    "name" => $u->name,
                    "img" => "/api/profilePic/$u->id"
                ],
                User::where('is_student', true)->get()->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    public function show(Request $request)
    {
        $user = Auth::user();

        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('classroomId');

        $requestedClassroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        return Inertia::render('routes/Admin/Classroom', [
            "classroom" => $requestedClassroom == null ?
                null
                :
                ClassroomDTO::fromClassroomModel($requestedClassroom),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    public function edit(Request $request)
    {
        $user = Auth::user();

        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('classroomId');

        $requestedClassroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        return Inertia::render('routes/Admin/ManageClassroom', [
            "classroom" => $requestedClassroom == null ?
                null
                :
                ClassroomDTO::fromClassroomModel($requestedClassroom),
            "courses" => array_map(
                fn ($c) => [
                    "id" => $c->id,
                    "name" => $c->name
                ],
                Course::get()->all()
            ),
            "courseSubjects" => array_reduce(
                CourseSubject::get()->all(),
                function ($carry, $item) {
                    $courseId = $item->course->id;

                    if (!\array_key_exists($courseId, $carry))
                        $carry[$courseId] = [];

                    $carry[$courseId][] = [
                        "id" => $item->id,
                        "name" => $item->name
                    ];

                    return $carry;
                },
                []
            ),
            "teachers" => array_map(
                fn ($u) => [
                    "id" => $u->id,
                    "name" => $u->name,
                    "img" => "/api/profilePic/$u->id"
                ],
                User::where('is_teacher', true)->get()->all()
            ),
            "monitors" => array_map(
                fn ($u) => [
                    "id" => $u->id,
                    "name" => $u->name,
                    "img" => "/api/profilePic/$u->id"
                ],
                User::where('is_monitor', true)->get()->all()
            ),
            "students" => array_map(
                fn ($u) => [
                    "id" => $u->id,
                    "name" => $u->name,
                    "img" => "/api/profilePic/$u->id"
                ],
                User::where('is_student', true)->get()->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }
}
