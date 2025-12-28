<?php

namespace App\Http\Middleware\Web;

use Closure;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserFirstAccessRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->routeIs("admin.first-access"))
            return $next($request);

        $token = $request->route("token");

        $record = DB::table('first_access_tokens')
            ->where('token', $token)
            ->first();

        if ($record == null)
            redirect("/");

        return $next($request);
    }
}
