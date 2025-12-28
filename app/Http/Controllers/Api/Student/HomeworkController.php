<?php

namespace App\Http\Controllers\Api\Student;

use App\Models\Course;
use App\Models\HomeworkTextResponse;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class HomeworkController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_student)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId,
            "classElementId" => $classElementId
        ] = $request->route()->parameters();

        try {
            if (!Course::where('id', $courseId)->exists())
                throw ValidationException::withMessages([ "course" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->where('id', $subjectId)
                ->exists()
            )
                throw ValidationException::withMessages([ "subject" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->where('id', $classroomId)
                ->exists()
            )
                throw ValidationException::withMessages([ "classroom" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->students()
                ->where('id', $user->id)
                ->exists()
            )
                throw ValidationException::withMessages([ "student" => "validation.is_student" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->classes()
                ->where('id', $classId)
                ->exists()
            )
                throw ValidationException::withMessages([ "class" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->classes()
                ->find($classId)
                ->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'homework']
                ])
                ->exists()
            )
                throw ValidationException::withMessages([ "homework" => "validation.exists" ]);

            $homework = Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->classes()
                ->find($classId)
                ->elements()
                ->where([
                    ['element_id', $classElementId],
                    ['type', 'homework']
                ])
                ->first()
                ->homework;

            $newResponse = $request->validate([
                "text" => [Rule::requiredIf((bool) $homework->is_text), 'string'],
                "files" => [Rule::requiredIf((bool) $homework->is_file), 'array', 'min:1'],
                "files.*" => [Rule::file()->extensions($homework->file_types)]
            ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        if ($homework->is_text)
            HomeworkTextResponse::create([
                "text" => $newResponse["text"],
                "user_id" => $user->id,
                "homework_id" => $homework->id
            ]);
    }
}