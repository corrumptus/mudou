<?php

namespace App\Data\Announcements;

use App\Models\Announcement as AnnouncementModel;
use App\Models\User;

class Announcement {
    public static function fromAnnoucementModel(
        AnnouncementModel $announcement,
        User $user
    ) {
        return [
            "id" => $announcement->id,
            "title" => $announcement->title,
            "description" => $announcement->description,
            "saw" => $announcement->viewers()->where('user_id', $user->id)->first()->viewers->saw
        ];
    }
}