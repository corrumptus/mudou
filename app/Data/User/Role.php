<?php

namespace App\Data\User;

use App\Models\Role as RoleModel;

class Role {
    public static function fromRoleModel(RoleModel $role) {
        return [
            "id" => $role->id,
            "name" => $role->name,
            "linked" => $role->linked
        ];
    }
}