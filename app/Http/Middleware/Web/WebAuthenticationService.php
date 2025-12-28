<?php

namespace App\Http\Middleware\Web;

use Closure;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\JWTHelper;
use App\Models\User;

class WebAuthenticationService
{
    public static string $COOKIE_NAME = 'my_token';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie($this::$COOKIE_NAME);

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
        if ($request->is("/"))
            return $next($request);

        $token = $request->route("token");

        if ($token != null)
            if ($request->routeIs("admin.user.firstAccess"))
                return $next($request);

        return redirect("/");
    }
}
