<?php

namespace App\Http\Controllers\Web\Admin;

use App\Data\Course\ReferenceCourse;
use App\Models\Course;
use App\Data\CourseSubject\CourseSubject as CourseSubjectDTO;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseSubjectController
{
    public function create()
    {
        $user = Auth::user();

        return Inertia::render('routes/Admin/ManageCourseSubject', [
            "courses" => array_reduce(Course::get()->all(), function ($carry, $item) {
                $carry[$item->id] = ReferenceCourse::fromCourseModel($item);

                return $carry;
            }, []),
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

        $subjectName = $request->route('subjectName');

        $requestedSubject = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName);

        return Inertia::render('routes/Admin/CourseSubject', [
            "courseSubject" => $requestedSubject == null ?
                null
                :
                CourseSubjectDTO::fromCourseSubjectModel($requestedSubject),
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

        $subjectName = $request->route('subjectName');

        $requestedSubject = Course::firstWhere('name', $courseName)
            ?->subjects()
            ->firstWhere('name', $subjectName);

        return Inertia::render('routes/Admin/ManageCourseSubject', [
            "courseSubject" => $requestedSubject == null ?
                null
                :
                CourseSubjectDTO::fromCourseSubjectModel($requestedSubject),
            "courses" => array_reduce(Course::get()->all(), function ($carry, $item) {
                $carry[$item->id] = ReferenceCourse::fromCourseModel($item);

                return $carry;
            }, []),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }
}
