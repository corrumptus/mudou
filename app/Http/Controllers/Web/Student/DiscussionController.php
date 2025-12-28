<?php

namespace App\Http\Controllers\Web\Student;

use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DiscussionController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('classroomId');

        $user = Auth::user();

        return Inertia::render(
            'routes/Student/NewDiscussion',
            ClassroomController::pageProps($courseName, $subjectName, $classroomId, $user)
        );
    }
}