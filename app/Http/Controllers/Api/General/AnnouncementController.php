<?php

namespace App\Http\Controllers\Api\General;

use App\Models\Course;
use Auth;
use Illuminate\Http\Request;

class AnnouncementController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "announcementId" => $announcementId,
        ] = $request->route()->parameters();

        $user = Auth::user();

        $announcement = Course::find($courseId)
            ->subjects()
            ->find($subjectId)
            ->classrooms()
            ->find($classroomId)
            ->announcements()
            ->find($announcementId);

        if ($announcement == null)
            return response()->json([ "error" => "Algum campo está errado" ], 400);

        $announcement->viewers()->where('id', $user->id)->pivot->update([
            "saw" => true
        ]);
    }
}