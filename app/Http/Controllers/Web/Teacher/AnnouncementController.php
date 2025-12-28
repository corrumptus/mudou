<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Models\Course;
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
        $courseName = $request->route("courseName");

        $subjectName = $request->route("subjectName");

        $classroomId = $request->route("classroomId");

        $user = Auth::user();

        $classroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        if (
            $classroom == null
            ||
            !$classroom->teachers()->where('id', $user->id)->exists()
        )
            return redirect('/teacher/course-subject');

        return Inertia::render(
            'routes/Teacher/NewAnnouncement',
            ClassroomController::pageProps(
                $courseName,
                $subjectName,
                $classroomId,
                $user
            )
        );
    }
}