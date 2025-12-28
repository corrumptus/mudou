<?php

namespace App\Data\CourseSubject;

use App\Models\CourseSubject as CourseSubjectModel;

class CourseSubject {
    public static function fromCourseSubjectModel(CourseSubjectModel $model) {
        return [
            "id" => $model->id,
            "name" => $model->name,
            "code" => $model->code,
            "course" => [
                "id" => $model->course->id,
                "name" => $model->course->name
            ]
        ];
    }
}