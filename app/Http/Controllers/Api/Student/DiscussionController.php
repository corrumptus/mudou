<?php

namespace App\Http\Controllers\Api\Student;

use App\Models\Discussion;
use App\Models\Course;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DiscussionController
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
            $newDiscussion = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:4096']
            ]);

            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (!$classroom->students()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_student" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        Discussion::create([
            "title" => $newDiscussion["title"],
            "description" => $newDiscussion["description"],
            "created_at" => now()->getTimestamp(),
            "classroom_id" => $classroom->id,
            "user_id" => $user->id
        ]);
    }
}