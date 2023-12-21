<?php

namespace App\Http\Controllers;

use Illuminate\{Foundation\Auth\Access\AuthorizesRequests,
    Foundation\Validation\ValidatesRequests,
    Routing\Controller as BaseController};
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0',
    description: '오윈 PROXY 문서',
    title: 'OWin PROXY DOCUMENT'
)]
#[OA\Server(
    url: 'https://owin-proxy-sales-dev.owinpay.com/api',
    description: '개발서버 uri'
)]
#[OA\Server(
    url: 'https://owin-proxy-sales.owinpay.com/api',
    description: '운영서버 uri'
)]

#[OA\Components([
    new OA\Response(response: 'Exception', description: '400: Bad Request<br />401: Authorization, 403: Access Denied<br />404: Not Found<br />405: Method Not Allowed<br />429: TooManyRequests<br />500: Others', content: new OA\JsonContent(properties: [
        new OA\Property(property: 'code', description: '결과코드', type: 'string'),
        new OA\Property(property: 'message', description: '결과메시지', type: 'string')
    ]))
])]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
