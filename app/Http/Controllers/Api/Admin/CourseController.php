<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\Course\Course as CourseDTO;
use App\Models\Course;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CourseController
{
    public function create(Request $request) {
        $user = Auth::user();

        if (!$user->is_admin)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        try {
            $newCourse = $request->validate([
                "name" => ['required', 'string', 'max:255', 'unique:courses'],
                "code" => ['required', 'string', 'max:255', 'unique:courses'],
                "amountSemesters" => ['required', 'integer', 'gt:0']
            ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        Course::create([
            "name" => $newCourse["name"],
            "code" => $newCourse["code"],
            "amount_semesters" => $newCourse["amountSemesters"]
        ]);
    }

    public function edit(Request $request) {
        $user = Auth::user();

        if (!$user->is_admin)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        $courseId = $request->route("courseId");

        try {
            $newCourse = $request->validate([
                "name" => ['required', 'string', 'max:255', 'unique:courses'],
                "code" => ['required', 'string', 'max:255', 'unique:courses'],
                "amountSemesters" => ['required', 'integer', 'gt:0']
            ]);

            if (!Course::where('id', $courseId)->exists())
                throw ValidationException::withMessages([ "course" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $requestedCourse = Course::find($courseId);

        $requestedCourse->name = $newCourse["name"];
        $requestedCourse->code = $newCourse["code"];
        $requestedCourse->amount_semesters = $newCourse["amountSemesters"];

        $requestedCourse->save();

        return response()->json([
            "course" => CourseDTO::fromCourseModel($requestedCourse)
        ]);
    }
}
