<?php

namespace App\Http\Controllers\Web\Student;

use App\Data\Classroom\Classroom as ClassroomDTO;
use App\Models\Course;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClassroomController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('classroomId');

        $user = Auth::user();

        return Inertia::render(
            'routes/Student/Classroom',
            [
                ...$this->pageProps($courseName, $subjectName, $classroomId, $user),
                "type" => "student"
            ]
        );
    }

    public static function pageProps(string $courseName, string $subjectName, int $classroomId, User $user) {
        $classroom = ClassroomController::query($courseName, $subjectName, $classroomId, $user);

        return $classroom == null ?
            ClassroomDTO::fromNullPath($courseName, $subjectName, $classroomId)
            :
            ClassroomDTO::fromClassroomModelIncomplete($classroom);
    }

    private static function query(string $courseName, string $subjectName, int $classroomId, User $user) {
        $classroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        if ($classroom == null)
            return null;

        if (!$classroom->students()->where('id', $user->id)->exists())
            return null;

        return $classroom;
    }
}
