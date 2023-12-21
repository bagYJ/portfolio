<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\{Requests\Bizcall\AutoMapping, Utils\Common};
use App\Responses\Bizcall\{AutoMapping as AutoMappingResponse,
    CloseMapping as CloseMappingResponse,
    GetVns as GetVnsResponse};
use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'bizcall', description: '안심번호')]
class Bizcall extends Controller
{
    private readonly \App\Services\Dev\Bizcall|\App\Services\Production\Bizcall $service;

    public function __construct()
    {
        $this->service = Common::getService('Bizcall');
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Get(
        path: '/bizcall',
        summary: '안심번호리스트',
        tags: ['bizcall'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: GetVnsResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function getVns(): JsonResponse
    {
        $response = $this->service::getVns();
        return response()->json((new GetVnsResponse($response)));
    }

    /**
     * @param AutoMapping $request
     * @return JsonResponse
     */
    #[OA\Post(
        path: '/bizcall/auto-mapping',
        summary: '안심번호 자동매핑',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(AutoMapping::class))),
        tags: ['bizcall'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: AutoMappingResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function autoMapping(AutoMapping $request): JsonResponse
    {
        $response = $this->service::autoMapping($request);
        return response()->json((new AutoMappingResponse($response)));
    }

    /**
     * @param string $virtualNumber
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Put(
        path: '/bizcall/close-mapping/{virtualNumber}',
        summary: '안심번호 자동매핑',
        tags: ['bizcall'],
        parameters: [
            new OA\Parameter(name: 'virtualNumber', in: 'path', required: true, schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: CloseMappingResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function closeMapping(string $virtualNumber): JsonResponse
    {
        $response = $this->service::closeMapping($virtualNumber);
        return response()->json((new CloseMappingResponse($response)));
    }
}
