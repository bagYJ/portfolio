<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Requests\Ark\Request;
use App\Responses\Ark\Response;
use App\Utils\Common;
use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'ark', description: '아크서버')]
class Ark extends Controller
{
    private readonly \App\Services\Dev\Ark|\App\Services\Production\Ark $service;

    public function __construct()
    {
        $this->service = Common::getService('Ark');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/ark/payment',
        summary: '주유주문정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Request::class))),
        tags: ['ark'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function payment(Request $request): JsonResponse
    {
        $response = $this->service::socket(getenv('ARK_PAYMENT'), $request->body);
        return response()->json(new Response($response));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/ark/order',
        summary: 'FNB주문정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Request::class))),
        tags: ['ark'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function order(Request $request): JsonResponse
    {
        $response = $this->service::socket(getenv('ARK_ORDER'), $request->body);
        return response()->json(new Response($response));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/ark/call',
        summary: 'FNB도착알림 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Request::class))),
        tags: ['ark'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function call(Request $request): JsonResponse
    {
        $response = $this->service::socket(getenv('ARK_CALL'), $request->body);
        return response()->json(new Response($response));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/ark/preset',
        summary: '주유프리셋정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Request::class))),
        tags: ['ark'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function preset(Request $request): JsonResponse
    {
        $response = $this->service::socket(getenv('ARK_PRESET'), $request->body);
        return response()->json(new Response($response));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/ark/wash',
        summary: '자동세차주문정보 전달',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Request::class))),
        tags: ['ark'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function wash(Request $request): JsonResponse
    {
        $response = $this->service::socket(getenv('ARK_WASH'), $request->body);
        return response()->json(new Response($response));
    }
}
