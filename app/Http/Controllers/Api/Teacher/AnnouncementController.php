<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Models\Announcement;
use App\Models\Course;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AnnouncementController
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
            $newAnnouncement = $request->validate([
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

            if (!$classroom->teachers()->where('id', $user->id)->exists())
                throw ValidationException::withMessages([ "user" => "validation.is_teacher" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $announcement = Announcement::create([
            "title" => $newAnnouncement["title"],
            "description" => $newAnnouncement["description"],
            "created_at" => now()->getTimestamp(),
            "classroom_id" => $classroom->id,
            "user_id" => $user->id
        ]);

        $announcement->viewers()->attach(
            [
                ...$classroom->teachers->filter(fn ($u) => $u->id != $user->id)->map(fn ($u) => $u->id)->all(),
                ...$classroom->monitors->map(fn ($u) => $u->id)->all(),
                ...$classroom->students->map(fn ($u) => $u->id)->all()
            ],
            [
                "saw" => false
            ]
        );

        $announcement->viewers()->attach($user->id, [ "saw" => true ]);
    }
}