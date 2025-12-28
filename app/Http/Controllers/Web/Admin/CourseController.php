<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Course;
use App\Data\Course\Course as CourseDTO;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController
{
    public function create()
    {
        $user = Auth::user();

        return Inertia::render('routes/Admin/ManageCourse', [
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    public function show(Request $request)
    {
        $user = Auth::user();

        $courseName = $request->route('courseName');

        $requestedCourse = Course::firstWhere('name', $courseName);

        return Inertia::render('routes/Admin/Course', [
            "course" => $requestedCourse == null ?
                null
                :
                CourseDTO::fromCourseModel($requestedCourse),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    public function edit(Request $request)
    {
        $user = Auth::user();

        $courseName = $request->route('courseName');

        $requestedCourse = Course::firstWhere('name', $courseName);

        return Inertia::render('routes/Admin/ManageCourse', [
            "course" => $requestedCourse == null ?
                null
                :
                CourseDTO::fromCourseModel($requestedCourse),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }
}
