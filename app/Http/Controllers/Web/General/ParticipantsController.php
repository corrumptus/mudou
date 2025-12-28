<?php

namespace App\Http\Controllers\Web\General;

use App\Data\Classroom\Classroom as ClassroomDTO;
use App\Data\User\User as UserDTO;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParticipantsController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
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

        return Inertia::render('routes/General/Participants', $this->isClassroomParticipant($classroom, $user) ? 
            [
                ...ClassroomDTO::fromClassroomModelIncomplete($classroom),
                "participants" => [
                    "teachers" => array_map(
                        fn ($u) => UserDTO::fromUserModelIncomplete($u),
                        $classroom->teachers->all()
                    ),
                    "monitors" => array_map(
                        fn ($u) => UserDTO::fromUserModelIncomplete($u),
                        $classroom->monitors->all()
                    ),
                    "students" => array_map(
                        fn ($u) => UserDTO::fromUserModelIncomplete($u),
                        $classroom->students->all()
                    )
                ]
            ]
            :
            ClassroomDTO::fromNullPath($courseName, $subjectName, $classroomId)
        );
    }

    public function isClassroomParticipant(?Classroom $classroom, User $user) {
        if ($classroom == null)
            return false;

        return
            $classroom->teachers()->find($user->id) != null
            ||
            $classroom->monitors()->find($user->id) != null
            ||
            $classroom->students()->find($user->id) != null;
    }
}
