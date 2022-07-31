<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\AppType;
use App\Exceptions\OwinException;
use App\Exceptions\TMapException;
use App\Utils\Code;
use Closure;
use Illuminate\Http\Request;

class CheckAppType
{
    private array $appType = [
        'rkm' => 'AVN',
//        'tmap' => 'TMAP_AUTO'
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        collect($this->appType)->map(function ($app, $dir) use ($request) {
            if ($request->is(sprintf('*/%s/*', $dir)) && getAppType() != AppType::case($app)) {
                match (AppType::case($app)) {
                    AppType::TMAP_AUTO => throw new TMapException('C0105', 400),
                    default => throw new OwinException(Code::message('C0105'))
                };
            }
        });

        return $response;
    }
}
