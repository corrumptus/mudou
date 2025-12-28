<?php

namespace App\Data\Course;

use App\Models\Course as CourseModel;

class Course {
    public static function fromCourseModel(CourseModel $course) {
        return [
            "id" => $course->id,
            "name" => $course->name,
            "code" => $course->code,
            "amountSemesters" => $course->amount_semesters
        ];
    }

    public static function fromCourseModelIncomplete(CourseModel $course) {
        return [
            "id" => $course->id,
            "name" => $course->name
        ];
    }
}