<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Data\Homework\Homework as HomeworkDTO;
use App\Models\ClassElement;
use App\Models\Course;
use App\Models\Homework;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HomeworkController
{
    public function create(Request $request) {
        $user = Auth::user();

        if (!$user->is_teacher)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        [
            "courseId" => $courseId,
            "subjectId" => $subjectId,
            "classroomId" => $classroomId,
            "classId" => $classId
        ] = $request->route()->parameters();

        try {
            $newHomework = $request->validate([
                "title" => ['required', 'string'],
                "description" => ['required', 'string', 'max:5120'],
                "beginDate" => ['required', 'date'],
                "beginTime" => ['required'],
                "closeDate" => ['required', 'date'],
                "closeTime" => ['required'],
                "canAcceptAfterClose" => ['required', 'boolean'],
                // "group" => ['present', 'integer', 'exists:groups,id'],
                "isText" => ['required', 'boolean'],
                "maxAmountCaracteres" => ['required', 'integer'],
                "isFile" => ['required', 'boolean'],
                "maxAmountFiles" => ['required', 'integer'],
                "fileTypes" => ['present']
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
                throw ValidationException::withMessages([ "classroom" => "validation.exists" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->teachers()
                ->where('id', $user->id)
                ->exists()
            )
                throw ValidationException::withMessages([ "teacher" => "validation.is_teacher" ]);

            if (!Course::find($courseId)
                ->subjects()
                ->find($subjectId)
                ->classrooms()
                ->find($classroomId)
                ->classes()
                ->where('id', $classId)
                ->exists()
            )
                throw ValidationException::withMessages([ "class" => "validation.exists" ]);

            try {
                Carbon::parse($newHomework["beginDate"]);
                Carbon::parse($newHomework["closeDate"]);
            } catch (Exception $e) {
                throw ValidationException::withMessages([ "date" => "validation.date" ]);
            }

            try {
                Carbon::parse($newHomework["beginTime"]);
                Carbon::parse($newHomework["closeTime"]);
            } catch (Exception $e) {
                throw ValidationException::withMessages([ "time" => "validation.time" ]);
            }

            try {
                if (
                    !Carbon::createFromFormat(
                        'Y-m-d H:i',
                        Carbon::parse($newHomework["closeDate"])->format('Y-m-d')
                        . " " .
                        Carbon::parse($newHomework["closeTime"])->format('H:i')
                    )->isAfter(
                        Carbon::createFromFormat(
                            'Y-m-d H:i',
                            Carbon::parse($newHomework["beginDate"])->format('Y-m-d')
                            . " " .
                            Carbon::parse($newHomework["beginTime"])->format('H:i')
                        )
                    )
                )
                    throw new Exception("O término deve ser depois do início");
            } catch (Exception $th) {
                throw ValidationException::withMessages([ "dateTime" => "validation.invalid_date_time_interval" ]);
            }

            if ($request->input('fileTypes') != null)
                $request->validate([ "fileTypes" => ['string']]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $homework = Homework::create([
            "title" => $newHomework["title"],
            "description" => $newHomework["description"],
            "begin_date_time" => Carbon::parse($newHomework["beginDate"])->format('Y-m-d') . " " . Carbon::parse($newHomework["beginTime"])->format('H:i'),
            "close_date_time" => Carbon::parse($newHomework["closeDate"])->format('Y-m-d') . " " . Carbon::parse($newHomework["closeTime"])->format('H:i'),
            "can_accept_after_close" => $newHomework["canAcceptAfterClose"],
            "is_text" => $newHomework["isText"],
            "max_amount_caracteres" => $newHomework["maxAmountCaracteres"],
            "is_file" => $newHomework["isFile"],
            "max_amount_files" => $newHomework["maxAmountFiles"],
            "file_types" => $newHomework["fileTypes"] == null ? "" : $newHomework["fileTypes"]
        ]);

        ClassElement::create([
            "name" => $newHomework["title"],
            "type" => "homework",
            "class_id" => $classId,
            "element_id" => $homework->id
        ]);
    }

    public function edit(Request $request) {
        throw new Exception("TODO: implements");
    }
}