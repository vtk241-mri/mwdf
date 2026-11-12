<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Пропускає далі, якщо роль користувача не нижча за $role
     *
     * @param Request $request
     * @param Closure $next
     * @param string $role
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()->hasRole($role)) {
            return response()->json(['data' => ['error' => 'Access denied']], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
