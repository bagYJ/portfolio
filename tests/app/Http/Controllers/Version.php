<?php

namespace Tests\app\Http\Controllers;

use OpenApi\Attributes as OA;

class Version extends Controller
{
    #[OA\Get(
        path: '/version',
        description: '앱 버전 확인',
        tags: ['version'],
        responses: [
            new OA\Response(
                response: 200, description: 'success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'result', description: '성공 여부', type: 'bool'),
                new OA\Property(property: 'version', description: '앱 버전', type: 'float'),
                new OA\Property(property: 'playstore_url', description: 'android 앱 링크', type: 'string'),
                new OA\Property(property: 'appstore_url', description: 'ios 앱 링크', type: 'string')
            ], type: 'object')
            ),
            new OA\Response(response: 500, description: 'api failed')
        ]
    )]
    public function get_test()
    {
    }
}
