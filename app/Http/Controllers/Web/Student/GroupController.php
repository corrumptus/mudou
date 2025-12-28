<?php

namespace App\Http\Controllers\Web\Student;

use App\Data\Group\Group as GroupDTO;
use App\Models\Course;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        [
            "courseName" => $courseName,
            "subjectName" => $subjectName,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        $user = Auth::user();

        $group = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
                ->where('id', $classroomId)
                ->whereHas('students', function ($query) use ($user) {
                    $query->where('id', $user->id);
                })
            ->first()
            ?->classes()
            ->find($classId)
            ?->elements()
            ->where('element_id', $classElementId)
            ->first()
            ?->group;

        return Inertia::render('routes/Student/Group', [
            ...ClassroomController::pageProps($courseName, $subjectName, $classroomId, $user),
            "group" => $group != null ?
                GroupDTO::fromGroupModel($group, $user)
                :
                null
        ]);
    }
}
