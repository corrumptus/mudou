<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\User\User as UserDTO;
use App\Helpers\JWTHelper;
use App\Http\Middleware\Web\WebAuthenticationService;
use App\Models\User;
use Auth;
use Cookie;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Str;

class UserController
{
    public function create(Request $request) {
        $user = Auth::user();

        if (!$user->is_admin)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        try {
            $newUser = $request->validate([
                "email" => ['required', 'email', 'unique:users'],
                "name" => ['required', 'string', 'max:255'],
                "birthDate" => ['required', Rule::date()->format('Y-m-d')->beforeToday()],
                "cpf" => ['required', 'string', 'max:11'],
                "img" => ['nullable'],
                "isAdmin" => ['required', 'bool'],
                "isTeacher" => ['required', 'bool'],
                "isStudent" => ['required', 'bool'],
                "isMonitor" => ['required', 'bool'],
                "roles" => ['nullable']
            ]);

            if ($request->input("img") != null)
                $request->validate([
                    "img" => ['image', Rule::imageFile()->extensions(['png', 'jpg', 'jpeg', 'webp'])->max(15360)]
                ]);

            if ($request->input("roles") != null)
                $request->validate([
                    "roles" => ['array'],
                    "roles.*" => [Rule::numeric()->integer(), Rule::exists('roles', 'id')]
                ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $hash = null;

        if ($request->input('img'))
            $hash = hash_file('SHA256', 'sha256', $request->file('img')->getRealPath());

        $createdUser = User::create([
            "email" => $newUser["email"],
            "name" => $newUser["name"],
            "birth_date" => $newUser["birthDate"],
            "cpf" => $newUser["cpf"],
            "is_admin" => $newUser["isAdmin"],
            "is_teacher" => $newUser["isTeacher"],
            "is_student" => $newUser["isStudent"],
            "is_monitor" => $newUser["isMonitor"],
            "profile_picture_hash" => $hash
        ]);

        if ($request->input("roles") != null)
            $createdUser->roles()->attach($newUser["roles"]);

        if ($request->input("img") != null)
            $request->file('img')->storeAs('imagens', "{$newUser['name']}-$hash");

        $token = Str::random(64);

        DB::table('first_access_tokens')->insert([
            'user_id' => $createdUser->id,
            'token' => $token
        ]);

        return response(
            [
                "link" => route(
                    "admin.user.firstAccess",
                    [
                        "token" => $token
                    ]
                )
            ],
            200
        );
    }

    public function first_access(Request $request) {
        $token = $request->route("token");

        $record = DB::table('first_access_tokens')
            ->where('token', $token)
            ->first();

        if ($record == null)
            return response()
                ->json([ "error" => "Link inválido" ], 404);

        try {
            [
                "password" => $password
            ] = $request->validate([
                "password" => ['required', 'string', 'min:8', 'max:64']
            ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "A senha deve conter pelo menos 8 caracteres" ], 400);
        }

        $user = User::find($record->user_id);

        $user->password = Hash::make($password);
        $user->is_first_access = false;

        $user->save();

        DB::table('first_access_tokens')
            ->where('user_id', $user->id)
            ->delete();

        $cookie = Cookie::make(
            WebAuthenticationService::$COOKIE_NAME,
            JWTHelper::encode($user),
            60,
            domain: config()->get('url'),
            sameSite: 'Strict'
        );

        return response("", 200)->cookie($cookie);
    }

    public function edit(Request $request) {
        $user = Auth::user();

        if (!$user->is_admin)
            response()->json([ "error" => "O usuário não tem permissão para criar um novo usuário no sistema"], 403);

        $id = $request->route("userId");

        try {
            $newUser = $request->validate([
                "email" => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
                "name" => ['required', 'string', 'max:255'],
                "password" => ['nullable', 'string', 'max:255'],
                "birthDate" => ['required', Rule::date()->format('Y-m-d')->beforeToday()],
                "cpf" => ['required', 'string', 'max:11'],
                "img" => ['nullable'],
                "isAdmin" => ['required', 'bool'],
                "isTeacher" => ['required', 'bool'],
                "isStudent" => ['required', 'bool'],
                "isMonitor" => ['required', 'bool'],
                "roles" => ['nullable']
            ]);

            if (!User::where('id', $id)->exists())
                throw ValidationException::withMessages([ "id" => 'validation.exists' ]);

            if ($request->input("img") != null)
                $request->validate([
                    "img" => ['image', Rule::imageFile()->extensions(['png', 'jpg', 'jpeg', 'webp'])->max(15360)]
                ]);

            if ($request->input("roles") != null)
                $request->validate([
                    "roles" => ['array'],
                    "roles.*" => [Rule::numeric()->integer(), Rule::exists('roles', 'id')]
                ]);
        } catch (ValidationException $e) {
            return response()
                ->json([ "error" => "Algum campo está errado" ], 400);
        }

        $requestedUser = User::find($id);

        $hash = null;

        if ($request->input('img') != null)
            $hash = hash_file('SHA256', 'sha256', $request->file('img')->getRealPath());

        $prevHash = $requestedUser->profile_picuture_hash;
        $prevName = $requestedUser->name;

        $requestedUser->email = $newUser["email"];
        $requestedUser->name = $newUser["name"];
        $requestedUser->birth_date = $newUser["birthDate"];
        $requestedUser->cpf = $newUser["cpf"];
        $requestedUser->is_admin = $newUser["isAdmin"];
        $requestedUser->is_teacher = $newUser["isTeacher"];
        $requestedUser->is_student = $newUser["isStudent"];
        $requestedUser->is_monitor = $newUser["isMonitor"];
        $requestedUser->profile_picture_hash = $hash;

        if ($request->input("password") != null)
            $requestedUser->password = Hash::make($newUser["password"]);

        if ($request->input("roles") != null) {
            $requestedUser->roles()->detach();
            $requestedUser->roles()->attach($newUser["roles"]);
        }

        if ($prevHash != $hash) {
            Storage::delete("imagens/$prevName-$prevHash");

            if ($request->input("img") != null)
                $request->file('img')->storeAs('imagens', "$requestedUser->name-$hash");
        }

        $requestedUser->save();

        return response()->json([
            "user" => UserDTO::fromUserModel($requestedUser)
        ]);
    }
}
