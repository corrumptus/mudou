<?php

namespace App\Data\Classroom;

use App\Data\User\User as UserDTO;
use App\Models\Classroom as ClassroomModel;
use App\Models\DayClass;
use Carbon\Carbon;

class Classroom {
    public static function fromNullPath(string $courseName, string $subjectName, int $classroomId) {
        return [
            "classroom" => [
                "id" => $classroomId,
                "courseName" => $courseName,
                "subjectName" => $subjectName
            ],
            "classes" => []
        ];
    }

    public static function fromClassroomModelIncomplete(ClassroomModel $classroom): array {
        return [
            "classroom" => [
                "id" => $classroom->id,
                "courseId" => $classroom->courseSubject->course->id,
                "courseName" => $classroom->courseSubject->course->name,
                "subjectId" => $classroom->courseSubject->id,
                "subjectName" => $classroom->courseSubject->name
            ],
            "classes" =>array_map(
                fn ($c) => self::fromClassModel($c),
                $classroom->classes()->orderBy('date')->get()->all()
            )
        ];
    }

    public static function fromClassroomModel(ClassroomModel $classroom) {
        return [
            "id" => $classroom->id,
            "periods" => array_map(
                fn ($p) => [
                    "dayOfTheWeek" => $p->dayOfTheWeek,
                    "beginTime" => $p->beginTime,
                    "closeTime" => $p->closeTime
                ],
                $classroom->periods->all()
            ),
            "beginDate" => Carbon::parse($classroom->begin_date)->format('Y-m-d'),
            "closeDate" => Carbon::parse($classroom->close_date)->format('Y-m-d'),
            "subject" => [
                "id" => $classroom->courseSubject->id,
                "name" => $classroom->courseSubject->name,
                "course" => [
                    "id" => $classroom->courseSubject->course->id,
                    "name" => $classroom->courseSubject->course->name
                ]
            ],
            "teachers" => array_map(
                fn ($t) => UserDTO::fromUserModelIncomplete($t),
                $classroom->teachers()->get()->all()
            ),
            "monitors" => array_map(
                fn ($m) => UserDTO::fromUserModelIncomplete($m),
                $classroom->monitors()->get()->all()
            ),
            "students" => array_map(
                fn ($s) => UserDTO::fromUserModelIncomplete($s),
                $classroom->students()->get()->all()
            )
        ];
    }

    public static function fromCourseSubject(ClassroomModel $classroom): array {
        $courseSubject = $classroom->courseSubject;

        return [
            "id" => $courseSubject->id,
            "name" => $courseSubject->name,
            "code" => $courseSubject->code,
            "color" => $courseSubject->color,
            "course" => $courseSubject->course->name,
            "classroomId" => $classroom->id
        ];
    }

    private static function fromClassModel(DayClass $class) {
        return [
            "id" => $class->id,
            "name" => $class->name,
            "elements" => $class->elements
                ->map(fn ($e) => [
                    "id" => $e->id,
                    "name" => $e->name,
                    "type" => $e->type,
                    "elementId" => $e->element_id
                ])
                ->all()
        ];
    }
}