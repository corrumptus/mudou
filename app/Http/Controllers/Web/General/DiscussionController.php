<?php

namespace App\Http\Controllers\Web\General;

use App\Data\Discussion\Discussion;
use App\Data\Classroom\Classroom as ClassroomDTO;
use App\Http\Controllers\Web\Student\ClassroomController;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DiscussionController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courseName = $request->route('courseName');

        $subjectName = $request->route('subjectName');

        $classroomId = $request->route('classroomId');

        $user = Auth::user();

        $classroom = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId);

        $classroomUserType = $this->classroomUserType($classroom, $user);

        if ($classroomUserType == null)
            return Inertia::render(
                'routes/General/Forun', 
                ClassroomDTO::fromNullPath($courseName, $subjectName, $classroomId)
            );

        $discussions = $classroom
            ->discussions()
            ->orderBy('created_at')
            ->get()
            ->all();

        return match ($classroomUserType) {
            'teacher' => $this->renderForTeacher($courseName, $subjectName, $classroomId, $discussions, $user),
            // 'monitor' => $this->renderForTeacher($courseName, $subjectName, $classroomId, $discussions, $user),
            'student' => $this->renderForStudent($courseName, $subjectName, $classroomId, $discussions, $user),
        };
    }

    private function classroomUserType(Classroom $classroom, User $user) {
        if ($classroom == null)
            return null;
        
        if ($classroom->teachers()->where('id', $user->id)->exists())
            return "teacher";

        if ($classroom->monitors()->where('id', $user->id)->exists())
            return "monitor";

        if ($classroom->students()->where('id', $user->id)->exists())
            return "student";

        return null;
    }

    private function renderForTeacher(
        string $courseName,
        string $subjectName,
        int $classroomId,
        array $discussions,
        User $user
    ) {
        return Inertia::render('routes/General/Forun', [
            ...\App\Http\Controllers\Web\Teacher\ClassroomController::pageProps($courseName, $subjectName, $classroomId, $user),
            "discussions" => $discussions == null ?
                null
                :
                array_map(
                    fn ($d) => Discussion::listElementFromDiscussionModel($d),
                    $discussions
                ),
            "type" => "teacher"
        ]);
    }

    private function renderForStudent(
        string $courseName,
        string $subjectName,
        int $classroomId,
        array $discussions,
        User $user
    ) {
        return Inertia::render('routes/General/Forun', [
            ...ClassroomController::pageProps($courseName, $subjectName, $classroomId, $user),
            "discussions" => $discussions == null ?
                null
                :
                array_map(
                    fn ($d) => Discussion::listElementFromDiscussionModel($d),
                    $discussions
                ),
            "type" => "student"
        ]);
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
            "discussionId" => $discussionId
        ] = $request->route()->parameters();

        $user = Auth::user();

        $discussion = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName)
            ?->classrooms()
            ->find($classroomId)
            ?->discussions()
            ->find($discussionId);

        return Inertia::render('routes/General/Discussion', [
            ...ClassroomController::pageProps($courseName, $subjectName, $classroomId, $user),
            "discussion" => $discussion == null ?
                null
                :
                Discussion::fromDiscussionModel($discussion)
        ]);
    }
}
