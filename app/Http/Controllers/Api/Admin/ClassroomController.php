<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\Classroom\Classroom as ClassroomDTO;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClassroomController
{
    private array $possibleDayOfTheWeek = [
        "segunda-feira",
        "terça-feira",
        "quarta-feira",
        "quinta-feira",
        "sexta-feira",
        "sábado",
        "domingo"
    ];

    public function create(Request $request) {
        $user = Auth::user();

        if (!$user->is_admin)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        $courseId = $request->route("courseId");
        $subjectId = $request->route("subjectId");

        try {
            $newClassroom = $request->validate([
                "beginDate" => ['required', 'date'],
                "closeDate" => ['required', 'date', 'after:beginDate'],
                "periods" => ['required', 'array', 'min:1'],
                "periods.*" => ['required', 'array'],
                "periods.*.dayOfTheWeek" => ['required', Rule::in($this->possibleDayOfTheWeek)],
                "periods.*.beginTime" => ['required'],
                "periods.*.closeTime" => ['required'],
                "teachers" => ['required', 'array', 'min:1'],
                "teachers.*" => ['required', 'integer'],
                "monitors" => ['present', 'array'],
                "monitors.*" => ['required', 'integer'],
                "students" => ['required', 'array', 'min:1'],
                "students.*" => ['required', 'integer']
            ]);

            if (!Course::where('id', $courseId)->exists())
                throw ValidationException::withMessages([ "course" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->where('id', $subjectId)
                ->exists()
            )
                throw ValidationException::withMessages([ "subject" => "validation.exists" ]);

            foreach ($newClassroom["periods"] as $period) {
                try {
                    if (!Carbon::parse($period["closeTime"])->isAfter(Carbon::parse($period["beginTime"])))
                        throw new Exception("O término deve ser depois do início");
                } catch (Exception $th) {
                    throw ValidationException::withMessages([ "periods" => "validation.invalid_time_interval" ]);
                }
            }

            foreach ($newClassroom["teachers"] as $id) {
                $user = User::find($id);
                if ($user == null || $user->is_teacher == false)
                    throw ValidationException::withMessages([ "teachers" => "validation.no_teacher" ]);
            }

            foreach ($newClassroom["monitors"] as $id) {
                $user = User::find($id);
                if ($user == null || $user->is_monitor == false)
                    throw ValidationException::withMessages([ "monitors" => "validation.no_monitor" ]);
            }

            foreach ($newClassroom["students"] as $id) {
                $user = User::find($id);
                if ($user == null || $user->is_student == false)
                    throw ValidationException::withMessages([ "students" => "validation.no_student" ]);
            }
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $classroom = Classroom::create([
            "begin_date" => $newClassroom["beginDate"],
            "close_date" => $newClassroom["closeDate"],
            "course_subject_id" => $subjectId
        ]);

        $periods = [];

        foreach ($newClassroom["periods"] as $period) {
            $periods[] = [
                "classroom_id" => $classroom->id,
                "dayOfTheWeek" => $period["dayOfTheWeek"],
                "beginTime" => Carbon::parse($period["beginTime"])->format('H:i'),
                "closeTime" => Carbon::parse($period["closeTime"])->format('H:i'),
            ];
        }

        DB::table('classroom_periods')->insert($periods);

        $classroom
            ->teachers()
            ->attach($newClassroom["teachers"]);

        $classroom
            ->monitors()
            ->attach($newClassroom["monitors"]);

        $classroom
            ->students()
            ->attach($newClassroom["students"]);
    }

    public function edit(Request $request) {
        $user = Auth::user();

        if (!$user->is_admin)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        $courseId = $request->route("courseId");
        $subjectId = $request->route("subjectId");
        $classroomId = $request->route("classroomId");

        try {
            $newClassroom = $request->validate([
                "beginDate" => ['required', 'date'],
                "closeDate" => ['required', 'date', 'after:beginDate'],
                "periods" => ['required', 'array', 'min:1'],
                "periods.*" => ['required', 'array'],
                "periods.*.dayOfTheWeek" => ['required', Rule::in($this->possibleDayOfTheWeek)],
                "periods.*.beginTime" => ['required'],
                "periods.*.closeTime" => ['required'],
                "teachers" => ['required', 'array', 'min:1'],
                "teachers.*" => ['required', 'integer'],
                "monitors" => ['present', 'array'],
                "monitors.*" => ['required', 'integer'],
                "students" => ['required', 'array', 'min:1'],
                "students.*" => ['required', 'integer']
            ]);

            if (!Course::where('id', $courseId)->exists())
                throw ValidationException::withMessages([ "course" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->where('id', $subjectId)
                ->exists()
            )
                throw ValidationException::withMessages([ "subject" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->where('id', $classroomId)
                ->exists()
            )
                throw ValidationException::withMessages([ "subject" => "validation.exists" ]);

            foreach ($newClassroom["periods"] as $period) {
                try {
                    if (!Carbon::parse($period["closeTime"])->isAfter(Carbon::parse($period["beginTime"])))
                        throw new Exception("O término deve ser depois do início");
                } catch (Exception $th) {
                    throw ValidationException::withMessages([ "periods" => "validation.invalid_time_interval" ]);
                }
            }

            foreach ($newClassroom["teachers"] as $id) {
                $teacher = User::find($id);
                if ($teacher == null || $teacher->is_teacher == false)
                    throw ValidationException::withMessages([ "teachers" => "validation.no_teacher" ]);
            }

            foreach ($newClassroom["monitors"] as $id) {
                $monitor = User::find($id);
                if ($monitor == null || $monitor->is_monitor == false)
                    throw ValidationException::withMessages([ "monitors" => "validation.no_monitor" ]);
            }

            foreach ($newClassroom["students"] as $id) {
                $student = User::find($id);
                if ($student == null || $student->is_student == false)
                    throw ValidationException::withMessages([ "students" => "validation.no_student" ]);
            }
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $requestedClassroom = Course::find($courseId)
            ->subjects()
            ->find($subjectId)
            ->classrooms()
            ->find($classroomId);

        $requestedClassroom->begin_date = $newClassroom["beginDate"];
        $requestedClassroom->close_date = $newClassroom["closeDate"];


        $requestedClassroom
            ->teachers()
            ->delete();
        $requestedClassroom
            ->teachers()
            ->attach($newClassroom["teachers"]);

        $requestedClassroom
            ->monitors()
            ->delete();
        $requestedClassroom
            ->monitors()
            ->attach($newClassroom["monitors"]);

        $requestedClassroom
            ->students()
            ->delete();
        $requestedClassroom
            ->students()
            ->attach($newClassroom["students"]);

        $requestedClassroom->save();

        $periods = [];

        foreach ($newClassroom["periods"] as $period) {
            $periods[] = [
                "classroom_id" => $classroomId,
                "dayOfTheWeek" => $period["dayOfTheWeek"],
                "beginTime" => Carbon::parse($period["beginTime"])->format('H:i'),
                "closeTime" => Carbon::parse($period["closeTime"])->format('H:i'),
            ];
        }

        DB::table('classroom_periods')->where('classroom_id', $classroomId)->delete();
        DB::table('classroom_periods')->insert($periods);

        return response()->json([
            "classroom" => ClassroomDTO::fromClassroomModel($requestedClassroom)
        ]);
    }
}