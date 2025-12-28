<?php

namespace App\Http\Controllers\Web\Student;

use App\Data\Homework\Homework as HomeworkDTO;
use App\Models\Course;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeworkController
{
    /**
     * Handle the incoming request.
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

        $homework = Course::firstWhere('name', $courseName)
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
            ?->homework;

        return Inertia::render('routes/Student/Homework', [
            ...ClassroomController::pageProps($courseName, $subjectName, $classroomId, $user),
            "homework" => $homework == null ?
                null
                :
                HomeworkDTO::fromHomeworkModel($homework),
            ...($homework != null ? [
                "response" => [
                    "text" => $homework->is_text ? $homework->textResponse()->first()?->text : null,
                    // "text" => $homework->is_text ? $homework->textResponse()->first()?->text : null
                ]
            ] : [])
        ]);
    }
}
