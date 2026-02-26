<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->attributes->get('currentUser');
        if (!$user) {
            $userId = $request->session()->get('user_id');
            if (!$userId) {
                return redirect('/login');
            }
            $user = User::find($userId);
        }

        if (!$user) {
            return redirect('/login');
        }

        $allowedRoles = array_filter(array_map('trim', explode('|', $role)));
        if ($allowedRoles && !in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
