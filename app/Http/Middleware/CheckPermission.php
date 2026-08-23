<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Superadmin (role_id = 1) bypasses all permission checks
        if (auth()->user()->role_id === 1) {
            return $next($request);
        }

        if (!auth()->user()->hasPermission($permission)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'غير مصرح لك بهذا الإجراء.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
