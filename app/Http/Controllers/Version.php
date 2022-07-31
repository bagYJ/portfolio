<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Utils\Code;
use Illuminate\Http\JsonResponse;


class Version extends Controller
{
    public function get(): JsonResponse
    {
        return response()->json(
            match (env('DEVELOPMENT')) {
                'real' => [
                    'result' => true,
                    'version' => (float)Code::conf('app.real.version'),
                    'playstore_url' => Code::conf('app.real.app_url.android'),
                    'appstore_url' => Code::conf('app.real.app_url.ios')
                ],
                'dev' => [
                    'result' => true,
                    'version' => (float)Code::conf('app.dev.version'),
                    'playstore_url' => Code::conf('app.dev.app_url.android'),
                    'appstore_url' => Code::conf('app.dev.app_url.ios'),
                ],
                default => []
            }
        );
    }
}
