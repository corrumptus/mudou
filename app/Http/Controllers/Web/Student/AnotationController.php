<?php

namespace App\Http\Controllers\Web\Student;

use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnotationController
{
    public function indexClassroom(Request $request)
    {
        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('subjectName');

        $user = Auth::user();

        return Inertia::render('routes/ClassroomAnotations');
    }

    public function self(Request $request)
    {
        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('subjectName');

        $user = Auth::user();

        return Inertia::render('routes/SelfAnotation');
    }

    public function selfEdit(Request $request)
    {
        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('subjectName');

        $user = Auth::user();

        return Inertia::render('routes/EditSelfAnotation');
    }

    public function monitor(Request $request)
    {
        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('classroomId');

        $user = Auth::user();

        return Inertia::render('routes/OtherAnotation');
    }

    public function public(Request $request)
    {
        $courseName = $request->route('courseName');

        $userId = $request->route('userId');

        $user = Auth::user();

        return Inertia::render('routes/OtherAnotation',);
    }
}
