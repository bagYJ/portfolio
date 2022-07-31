<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $response->header('Charset', 'UTF-8');
            $response->header('Content-Type','application/json; charset=UTF-8');
            $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
        return $response;
    }
}
