<?php

namespace App\Http\Controllers\Web\General;

use App\Data\Announcements\Announcement;
use App\Data\Classroom\Classroom as ClassroomDTO;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController
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

        $classroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        $classroomUserType = $this->classroomUserType($classroom, $user);

        if ($classroomUserType == null)
            return Inertia::render(
                'routes/General/Announcements', 
                ClassroomDTO::fromNullPath($courseName, $subjectName, $classroomId)
            );

        $announcements = $classroom
            ->announcements()
            ->orderBy('created_at')
            ->get()
            ->all();

        return match ($classroomUserType) {
            'teacher' => $this->renderForTeacher($courseName, $subjectName, $classroomId, $announcements, $user),
            // 'monitor' => $this->renderForTeacher($courseName, $subjectName, $classroomId, $announcements, $user),
            'student' => $this->renderForStudent($courseName, $subjectName, $classroomId, $announcements, $user),
        };
    }

    private function classroomUserType(Classroom $classroom, User $user) {
        if ($classroom == null)
            return null;
        
        if ($classroom->teachers()->where('id', $user->id)->exists())
            return "teacher";

        if ($classroom->monitors()->where('id', $user->id)->exists())
            return "monitor";

        if ($classroom->students()->where('id', $user->id)->exists())
            return "student";

        return null;
    }

    private function renderForTeacher(
        string $courseName,
        string $subjectName,
        int $classroomId,
        array $announcements,
        User $user
    ) {
        return Inertia::render('routes/General/Announcements', [
            ...\App\Http\Controllers\Web\Teacher\ClassroomController::pageProps($courseName, $subjectName, $classroomId, $user),
            "announcements" => $announcements == null ?
                null
                :
                array_map(
                    fn ($a) => Announcement::fromAnnoucementModel(
                        $a,
                        $user
                    ),
                    $announcements
                ),
            "type" => "teacher"
        ]);
    }

    private function renderForStudent(
        string $courseName,
        string $subjectName,
        int $classroomId,
        array $announcements,
        User $user
    ) {
        return Inertia::render('routes/General/Announcements', [
            ...\App\Http\Controllers\Web\Student\ClassroomController::pageProps($courseName, $subjectName, $classroomId, $user),
            "announcements" => $announcements == null ?
                null
                :
                array_map(
                    fn ($a) => Announcement::fromAnnoucementModel(
                        $a,
                        $user
                    ),
                    $announcements
                ),
            "type" => "student"
        ]);
    }
}
