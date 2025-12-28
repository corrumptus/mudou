<?php

namespace App\Data\Homework;

use App\Models\Homework as HomeworkModel;
use Carbon\Carbon;

class Homework
{
    public static function fromHomeworkModel(HomeworkModel $homeworkModel) {
        return [
            "id" => $homeworkModel->id,
            "title" => $homeworkModel->title,
            "description" => $homeworkModel->description,
            "beginDate" => Carbon::parse($homeworkModel->begin_date_time)->format('Y-m-d'),
            "beginTime" => Carbon::parse($homeworkModel->begin_date_time)->format('H:i'),
            "closeDate" => Carbon::parse($homeworkModel->close_date_time)->format('Y-m-d'),
            "closeTime" => Carbon::parse($homeworkModel->close_date_time)->format('H:i'),
            // "group_id" => $homework->group_id,
            "canAcceptAfterClose" => $homeworkModel->can_accept_after_close,
            "isText" => $homeworkModel->is_text,
            "maxAmountCaracteres" => $homeworkModel->max_amount_caracteres,
            "isFile" => $homeworkModel->is_file,
            "maxAmountFiles" => $homeworkModel->max_amount_files,
            "fileTypes" => $homeworkModel->file_types,
            "classId" => $homeworkModel->classElement->class_id
        ];
    }
}