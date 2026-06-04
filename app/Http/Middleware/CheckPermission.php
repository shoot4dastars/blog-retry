<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthenticated.');
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        $hasOwnSuffix = str_ends_with($permission, '|own');
        $basePermission = $hasOwnSuffix ? substr($permission, 0, -4) : $permission;

        $hasBasePermission = $user->hasPermissionTo($basePermission);

        if ($hasOwnSuffix) {
            $model = $request->route()->parameter('post') ?? $request->route()->parameter('comment');
            $isOwner = $model && (int) $model->user_id === (int) $user->id;
            if (!$hasBasePermission && !$isOwner) {
                abort(403, "No permission and not owner. User ID: {$user->id}, Post owner ID: " . ($model->user_id ?? 'null'));
            }
            return $next($request);
        }

        if (!$hasBasePermission) {
            abort(403, "You do not have the required permission: {$basePermission}");
        }

        return $next($request);
    }
}
