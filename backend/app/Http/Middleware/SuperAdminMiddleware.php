<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Access denied. Authentication required.');
        }

        $user = auth()->user();
        
        // Allow system super admin full access
        if ($user->isSystemSuperAdmin()) {
            return $next($request);
        }
        
        // For company user routes (like agents), allow company users
        if ($user->isCompanyUser()) {
            return $next($request);
        }

        abort(403, 'Access denied. This action requires admin privileges.');
    }
}
