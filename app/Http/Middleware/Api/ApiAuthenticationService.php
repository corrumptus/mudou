<?php

namespace App\Http\Middleware\Api;

use App\Helpers\JWTHelper;
use App\Models\User;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticationService
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie("my_token");

        $tokenUser = JWTHelper::decode($token);

        if ($tokenUser == null)
            return $this->handleUnauthenticated($request, $next);

        $user = User::find($tokenUser["id"]);

        if ($user != null)
            Auth::login($user);

        return $next($request);
    }

    public function handleUnauthenticated(Request $request, Closure $next): Response
    {
        if ($request->routeIs('api.login'))
            return $next($request);

        $token = $request->route("token");

        if ($token != null)
            if ($request->routeIs('api.first-access'))
                return $next($request);

        return response()
            ->json(["error" => "Must be authenticated to access the api"], 401);
    }
}
