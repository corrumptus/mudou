<?php

namespace App\Http\Controllers\Web\Student;

use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        [
            "courseName" => $courseName,
            "subjectName" => $subjectName,
            "classroomId" => $classroomId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        return Inertia::render('routes/Quiz');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        [
            "courseName" => $courseName,
            "subjectName" => $subjectName,
            "classroomId" => $classroomId,
            "classElementId" => $classElementId,
            "quizTryId" => $quizTryId
        ] = $request->route()->parameters();

        return Inertia::render('routes/QuizTry');
    }
}
