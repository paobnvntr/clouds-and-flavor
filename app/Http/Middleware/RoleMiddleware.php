<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $authUserRole = Auth::user()->role;

        switch ($role) {
            case 'admin':
                if ($authUserRole == 2) {
                    return $next($request);
                }
                break;

            case 'staff':
                if ($authUserRole == 1) {
                    return $next($request);
                }
                break;
            case 'user':
                if ($authUserRole == 0) {
                    return $next($request);
                }
                break;
        }

        switch ($authUserRole) {
            case 0:
                return redirect()->route('dashboard');
            case 1:
                return redirect()->route('staff.orders.dashboard');
            case 2:
                return redirect()->route('admin.dashboard');
        }
        return redirect()->route('login');
    }
}

