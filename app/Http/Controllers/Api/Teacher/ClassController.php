<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Data\Classroom\Classroom as ClassroomDTO;
use App\Models\Course;
use App\Models\DayClass;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $courseId = $request->route('courseId');

        $subjectId = $request->route('subjectId');

        $classroomId = $request->route('classroomId');

        $user = Auth::user();

        try {
            $newClass = $request->validate([
                'name' => ['required', 'string'],
                'date' => ['required', 'date']
            ]);

            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->teachers()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_teacher" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        DayClass::create([
            'name' => $newClass["name"],
            'date' => $newClass["date"],
            'classroom_id' => $classroomId
        ]);

        return ClassroomDTO::fromClassroomModelIncomplete($classroom);
    }
}