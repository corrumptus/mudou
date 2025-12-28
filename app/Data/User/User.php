<?php

namespace App\Data\User;

use App\Models\User as UserModel;
use Illuminate\Support\Carbon;

class User {
    public static function fromUserModel(UserModel $user) {
        return [
            "id" => $user->id,
            "email" => $user->email,
            "name" => $user->name,
            "birthDate" => Carbon::parse($user->birth_date)->format('Y-m-d'),
            "cpf" => $user->cpf,
            "img" => "/api/profilePic/{$user->id}",
            "isAdmin" => $user->is_admin,
            "isTeacher" => $user->is_teacher,
            "isStudent" => $user->is_student,
            "isMonitor" => $user->is_monitor,
            "roles" => array_map(
                fn ($r) => [
                    "id" => $r->id,
                    "name" => $r->name
                ],
                $user->roles->all()
            )
        ];
    }

    public static function fromUserModelIncomplete(UserModel $user) {
        return [
            "id" => $user->id,
            "name" => $user->name,
            "img" => "/api/profilePic/{$user->id}"
        ];
    }
}