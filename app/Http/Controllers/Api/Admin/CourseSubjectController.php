<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\CourseSubject\CourseSubject as CourseSubjectDTO;
use App\Models\Course;
use App\Models\CourseSubject;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseSubjectController
{
    public function create(Request $request) {
        $user = Auth::user();

        if (!$user->is_admin)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        $courseId = $request->route("courseId");

        try {
            $newCourse = $request->validate([
                "name" => ['required', 'string', 'max:255', 'unique:courses'],
                "code" => ['required', 'string', 'max:255', 'unique:courses'],
            ]);

            if (Course::find($courseId) == null)
                throw ValidationException::withMessages([ "course" => 'validation.exists' ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        CourseSubject::create([
            "name" => $newCourse["name"],
            "code" => $newCourse["code"],
            "color" => fake()->hexColor(),
            "course_id" => $courseId
        ]);
    }

    public function edit(Request $request) {
        $user = Auth::user();

        if (!$user->is_admin)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        $courseId = $request->route("courseId");
        $subjectId = $request->route("subjectId");

        try {
            $newCourse = $request->validate([
                "name" => ['required', 'string', 'max:255', 'unique:courses'],
                "code" => ['required', 'string', 'max:255', 'unique:courses']
            ]);

            if (!Course::where('id', $courseId)->exists())
                throw ValidationException::withMessages([ "course" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->where('id', $subjectId)
                ->exists()
            )
                throw ValidationException::withMessages([ "subject" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $requestedCourseSubject = Course::find($courseId)
            ->subjects()
            ->find($subjectId);

        $requestedCourseSubject->name = $newCourse["name"];
        $requestedCourseSubject->code = $newCourse["code"];

        $requestedCourseSubject->save();

        return response()->json([
            "course" => CourseSubjectDTO::fromCourseSubjectModel($requestedCourseSubject)
        ]);
    }
}