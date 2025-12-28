<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Middleware\Web\WebAuthenticationService;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use App\Helpers\JWTHelper;
use App\Models\User;

class LoginController
{
    /**
     * Handle the incoming request.
     */
    public function login(Request $request)
    {
        try {
            $loginUser = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string', 'min:8']
            ]);
        } catch (ValidationException $e) {
            return response()
                ->json(["error" => "É necessário mandar email e senha"], 400);
        }

        $user = User::where("email", $loginUser["email"])->first();

        if ($user == null)
            return response()->json(["error" => "Email ou senha inválidos"], 404);

        if (!Hash::check($loginUser["password"], $user->password))
            return response()->json(["error" => "Email ou senha inválidos"], 404);

        $cookie = Cookie::make(
            WebAuthenticationService::$COOKIE_NAME,
            JWTHelper::encode($user),
            60,
            domain: config()->get('url'),
            sameSite: 'Strict'
        );

        return response("", 200)->cookie($cookie);
    }

    public function logout(Request $request)
    {
        Cookie::expire(WebAuthenticationService::$COOKIE_NAME);

        response()->noContent(200);
    }
}
