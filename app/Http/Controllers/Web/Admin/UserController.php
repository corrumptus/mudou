<?php

namespace App\Http\Controllers\Web\Admin;

use App\Data\User\Role as RoleDTO;
use App\Data\User\User as UserDTO;
use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController
{
    public function create()
    {
        $user = Auth::user();

        return Inertia::render('routes/Admin/ManageUser', [
            "roles" => array_map(
                fn ($r) => RoleDTO::fromRoleModel($r),
                Role::get()->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    /**
     * Handle the incoming request.
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        $email = $request->route('email');

        $requestedUser = User::firstWhere('email', $email);

        return Inertia::render('routes/Admin/User', [
            "user" => $requestedUser == null ?
                null
                :
                UserDTO::fromUserModel($requestedUser),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }

    public function first_access(Request $request) {
        $token = $request->route("token");

        return Inertia::render('routes/Admin/UserConfirm', [
            "token" => $token
        ]);
    }

    public function edit(Request $request)
    {
        $user = Auth::user();

        $email = $request->route('email');

        $requestedUser = User::firstWhere('email', $email);

        return Inertia::render('routes/Admin/ManageUser', [
            "user" => $requestedUser == null ?
                null
                :
                UserDTO::fromUserModel($requestedUser),
            "roles" => array_map(
                fn ($r) => RoleDTO::fromRoleModel($r),
                Role::get()->all()
            ),
            "userTypes" => [
                "isAdmin" => $user->is_admin,
                "isTeacher" => $user->is_teacher,
                "isStudent" => $user->is_student,
                "isMonitor" => $user->is_monitor
            ]
        ]);
    }
}
