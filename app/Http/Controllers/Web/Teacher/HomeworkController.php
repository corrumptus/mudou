<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Models\Course;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeworkController
{
    public function create(Request $request)
    {
        [
            "courseName" => $courseName,
            "subjectName" => $subjectName,
            "classroomId" => $classroomId,
            "classId" => $classId
        ] = $request->route()->parameters();

        $user = Auth::user();

        $groups = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId)
            ?->classes()
            ->find($classId)
            ?->elements()
            ->where('type', 'group')
            ->get()
            ->map(fn ($e) => $e->group)
            ->all();

        return Inertia::render(
            'routes/Teacher/ManageHomework', [
                ...ClassroomController::pageProps(
                    $courseName,
                    $subjectName,
                    $classroomId,
                    $user
                ),
                "groups" => $groups == null ?
                    []
                    :
                    array_map(
                        fn ($g) => [
                            "id" => $g->id,
                            "name" => $g->name
                        ],
                        $groups
                    )
            ]
            
        );
    }
}