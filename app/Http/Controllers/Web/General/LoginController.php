<?php

namespace App\Http\Controllers\Web\General;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('routes/General/Login');
    }
}
