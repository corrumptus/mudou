<?php

namespace App\Http\Controllers\Web\Admin;

use App\Data\Classroom\Classroom as ClassroomDTO;
use App\Data\Course\Course as CourseDTO;
use App\Data\CourseSubject\CourseSubject as CourseSubjectDTO;
use App\Data\User\User as UserDTO;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\CourseSubject;
use App\Models\User;
use Auth;
use Inertia\Inertia;

class AdminController
{
    public function showPeople()
    {
        $user = Auth::user();

        return Inertia::render('routes/Admin/AdminPeople', [
            "users" => array_map(
                fn ($u) => UserDTO::fromUserModel($u),
                User::get()->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    public function showCourses()
    {
        $user = Auth::user();

        return Inertia::render('routes/Admin/AdminCourses', [
            "courses" => array_map(
                fn ($c) => CourseDTO::fromCourseModel($c),
                Course::get()->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    public function showSubjects()
    {
        $user = Auth::user();

        return Inertia::render('routes/Admin/AdminCourseSubjects', [
            "courseSubjects" => array_map(
                fn ($c) => CourseSubjectDTO::fromCourseSubjectModel($c),
                CourseSubject::get()->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    public function showClassrooms()
    {
        $user = Auth::user();

        return Inertia::render('routes/Admin/AdminClassrooms', [
            "classrooms" => array_map(
                fn ($c) => ClassroomDTO::fromClassroomModel($c),
                Classroom::get()->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }
}
