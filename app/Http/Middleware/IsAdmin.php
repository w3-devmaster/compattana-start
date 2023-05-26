<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::find(Auth::user()->id);
        if (!$user->hasRole('admins')) {
            if ($user->type == 100) {
                $user->assignRole('admins');
            } else {
                if (!$user->hasRole('users')) {
                    $user->assignRole('users');
                }
                return abort(503);
            }
        }

        return $next($request);
    }
}
