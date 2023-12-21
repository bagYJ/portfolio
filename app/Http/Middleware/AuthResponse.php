<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthResponse
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response)  $next
     * @return Response
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::channel('request')->info(sprintf('(%s) %s %s', $request->method(), $request->url(), $request->ip()), $request->all() ?? [$request->getContent()]);
        $response = $next($request);

        if (!$request->routeIs('no-auth')) {
            $repo = $request->header('repo') or throw new AuthorizationException();
            if ($request->header('authKey') != getenv(sprintf('%s_AUTH_KEY', strtoupper($repo)))) {
                throw new AuthorizationException();
            }
        }

        if ($response instanceof JsonResponse) {
            $response->header('Charset', 'UTF-8');
            $response->header('Content-Type','application/json; charset=UTF-8');
            $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            Log::channel('response')->info(sprintf('(%s) %s %s', $request->method(), $request->url(), $request->ip()), (array)$response->getOriginalContent());
        }

        return $response;
    }
}
