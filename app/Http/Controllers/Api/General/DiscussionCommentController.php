<?php

namespace App\Http\Controllers\Api\General;

use App\Data\Discussion\DiscussionComment as DiscussionCommentDTO;
use App\Models\Course;
use App\Models\DiscussionComment;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DiscussionCommentController
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
            "discussionId" => $discussionId,
        ] = $request->route()->parameters();

        $user = Auth::user();

        try {
            $newComment = $request->validate([
                "comment" => ['required', 'string', 'max:4096']
            ]);

            $classroom = Course::find($courseId)
                ?->subjects()
                ->find($subjectId)
                ?->classrooms()
                ->find($classroomId);

            if ($classroom == null)
                throw ValidationException::withMessages([ "classroom_id" => "validation.exists" ]);

            if (
                !$classroom->teachers()->where('id', $user->id)->exists()
                ||
                !$classroom->monitors()->where('id', $user->id)->exists()
                ||
                !$classroom->students()->where('id', $user->id)->exists()
            )
                throw ValidationException::withMessages([ "user" => "validation.is_participant" ]);

            $discussion = $classroom->discussions()->find($discussionId);

            if ($discussion == null)
                throw ValidationException::withMessages([ "discussion_id" => "validation.exists" ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $comment = DiscussionComment::create([
            "comment" => $newComment["comment"],
            "created_at" => now()->getTimestamp(),
            "discussion_id" => $discussion->id,
            "user_id" => $user->id
        ]);

        return response()->json(DiscussionCommentDTO::fromDiscussionCommentModel($comment));
    }
}