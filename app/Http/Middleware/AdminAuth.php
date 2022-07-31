<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->routeIs('admin-auth') && getenv('EXTERNAL_UUID') != $request->header('admin-auth-token')) {
            throw new AuthenticationException();
        }

        return $response;
    }
}
