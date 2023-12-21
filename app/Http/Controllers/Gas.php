<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\{Requests\Gas\Request, Responses\Gas\Response, Utils\Common};
use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'gas', description: '가스서버')]
class Gas extends Controller
{
    private readonly \App\Services\Dev\Gas|\App\Services\Production\Gas $service;

    public function __construct()
    {
        $this->service = Common::getService('Gas');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/gas/coupon',
        summary: '주유쿠폰등록정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Request::class))),
        tags: ['gas'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function coupon(Request $request): JsonResponse
    {
        $response = $this->service::socket(getenv('GAS_COUPON'), $request->body);
        return response()->json(new Response($response));
    }
}
