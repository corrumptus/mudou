<?php

namespace App\Http\Controllers\Web\Student;

use App\Data\Classroom\Classroom as ClassroomDTO;
use Auth;
use Inertia\Inertia;
use App\Models\Classroom;

class CourseSubjectController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $user = Auth::user();

        $classrooms = Classroom::whereHas(
            'students',
            function ($query) use ($user) {
                $query->where('users.email', $user->email);
            }
        )
            ->where("is_active", true)
            ->get();

        return Inertia::render('routes/General/CourseSubject', [
            "courseSubjects" => array_map(
                fn ($classroom) =>
                    ClassroomDTO::fromCourseSubject($classroom),
                $classrooms->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ],
            "type" => "student"
        ]);
    }
}
