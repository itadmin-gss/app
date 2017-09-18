<?php

namespace App\Http\Middleware;

use App\UserType;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $usertype = UserType::getUserTypeByID($user->type_id);
        if ($usertype != 'admin' && $usertype != 'user') {
            return redirect("/");
        }

        return $next($request);
    }
}
