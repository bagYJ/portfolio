<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Requests\Infine\Approval;
use App\Requests\Infine\ApprovalResult;
use App\Requests\Infine\Cancel;
use App\Requests\Page;
use App\Responses\Infine\Init;
use App\Responses\Infine\Lists;
use App\Responses\Response;
use App\Utils\Common;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'infine', description: '인파인')]
class Infine extends Controller
{
    private readonly \App\Services\Dev\Infine|\App\Services\Production\Infine $service;

    public function __construct()
    {
        $this->service = Common::getService('Infine');
    }

    /**
     * @param Page $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Get(
        path: '/infine/list',
        summary: '주유소 목록',
        tags: ['infine'],
        parameters: [
            new OA\Parameter(name: 'repo', in: 'header', required: true, schema: new OA\Schema(description: 'REPO (owin)', type: 'string')),
            new OA\Parameter(name: 'authKey', in: 'header', required: true, schema: new OA\Schema(description: '인증키', type: 'string')),
            new OA\Parameter(name: 'size', in: 'query', required: false, schema: new OA\Schema(description: '페이지당 항목 개수', type: 'integer')),
            new OA\Parameter(name: 'offset', in: 'query', required: false, schema: new OA\Schema(description: '페이지 offset', type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function list(Page $request): JsonResponse
    {
        $this->service::list($request);
        return response()->json(new Response());
    }

    /**
     * @param string $noOrder
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Post(
        path: '/infine/{noOrder}',
        summary: '주문요청 (프리셋세팅)',
        tags: ['infine'],
        parameters: [
            new OA\Parameter(name: 'repo', in: 'header', required: true, schema: new OA\Schema(description: 'REPO (owin)', type: 'string')),
            new OA\Parameter(name: 'authKey', in: 'header', required: true, schema: new OA\Schema(description: '인증키', type: 'string')),
            new OA\Parameter(name: 'noOrder', in: 'path', required: true, schema: new OA\Schema(description: '주문번호', type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function init(string $noOrder): JsonResponse
    {
        $this->service::init($noOrder);
        return response()->json(new Response());
    }

    /**
     * @param Cancel $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Post(
        path: '/infine/cancel',
        summary: '승인 취소 요청',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Cancel::class))),
        tags: ['infine'],
        parameters: [
            new OA\Parameter(name: 'repo', in: 'header', required: true, schema: new OA\Schema(description: 'REPO (owin)', type: 'string')),
            new OA\Parameter(name: 'authKey', in: 'header', required: true, schema: new OA\Schema(description: '인증키', type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function cancel(Cancel $request): JsonResponse
    {
        $this->service::cancel($request);
        return response()->json(new Response());
    }

    /**
     * @param Approval $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Post(
        path: '/infine/approval',
        summary: '강제 승인 요청',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Approval::class))),
        tags: ['infine'],
        parameters: [
            new OA\Parameter(name: 'repo', in: 'header', required: true, schema: new OA\Schema(description: 'REPO (owin)', type: 'string')),
            new OA\Parameter(name: 'authKey', in: 'header', required: true, schema: new OA\Schema(description: '인증키', type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Response::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function approval(Approval $request): JsonResponse
    {
        $this->service::approval($request);
        return response()->json(new Response());
    }

    /**
     * @param ApprovalResult $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/infine/approval-result',
        summary: '최종승인 결과',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(ApprovalResult::class))),
        tags: ['infine'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: \App\Responses\Infine\ApprovalResult::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function approvalResult(ApprovalResult $request): JsonResponse
    {
        $response = $this->service::approvalResult($request);
        return response()->json($response);
    }

    #[OA\Get(
        path: '/infine/mock/list',
        summary: '(목업)주유소 목록',
        tags: ['infine'],
        parameters: [
            new OA\Parameter(name: 'size', in: 'query', required: false, schema: new OA\Schema(description: '페이지당 항목 개수', type: 'integer')),
            new OA\Parameter(name: 'offset', in: 'query', required: false, schema: new OA\Schema(description: '페이지 offset', type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Lists::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function mockList(Page $request): JsonResponse
    {
        return response()->json([
            'code' => '0000',
            'message' => '정상처리',
            'data' => [
                'current_page' => null,
                'per_page' => null,
                'last_page' => null,
                'total_cnt' => 1,
                'shops' => [
                    [
                        'ds_uni' => 'A0000004',
                        'ds_poll_div' => 'GSC',
                        'nm_shop' => '(주)지에스이앤알 직영 하안단지주유소',
                        'ds_van_adr' => '경기 광명시 소하동 1275',
                        'ds_new_adr' => '경기 광명시 오리로 608 (소하동)',
                        'ds_tel' => '02-899-5202',
                        'ds_gis_x' => 301135,
                        'ds_gis_y' => 539928,
                        'ds_open_time' => '0700',
                        'ds_close_time' => '2359',
                        'at_lat' => 301135.1,
                        'at_lng' => 539928.1,
                        'yn_maint' => 'N',
                        'yn_cvs' => 'N',
                        'yn_car_wash' => 'N',
                        'yn_self' => 'Y',
                        'arks' => [
                            ['unit_id' => '01'],
                            ['unit_id' => '02'],
                            ['unit_id' => '03'],
                            ['unit_id' => '04'],
                            ['unit_id' => '05'],
                        ],
                        'prices' => [
                            [
                                'cd_gas_kind' => '204100',
                                'at_price' => 1554.0,
                                'dt_trade' => now()->format('Y-m-d'),
                                'tm_trade' => now()->format('H:i:s')
                            ],
                            [
                                'cd_gas_kind' => '204200',
                                'at_price' => 1819.0,
                                'dt_trade' => now()->format('Y-m-d'),
                                'tm_trade' => now()->format('H:i:s')
                            ],
                            [
                                'cd_gas_kind' => '204300',
                                'at_price' => 1557.0,
                                'dt_trade' => now()->format('Y-m-d'),
                                'tm_trade' => now()->format('H:i:s')
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    #[OA\Post(
        path: '/infine/mock/{noOrder}',
        summary: '(목업)주문요청 (프리셋세팅)',
        tags: ['infine'],
        parameters: [
            new OA\Parameter(name: 'noOrder', in: 'path', required: true, schema: new OA\Schema(description: '주문번호', type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Init::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function mockInit(string $noOrder): JsonResponse
    {
        return response()->json([
            'code' => '0000',
            'message' => '정상처리',
            'data' => [
                'no_order' => $noOrder,
                'infine_order' => $noOrder,
                'ds_uni' => 'A0000004',
                'no_nozzle' => '01',
                'nozzle_status' => '0',
                'result_code' => '0000',
                'result_msg' => '정상처리',
                'dt_approval_temp' => now()->format('Y-m-d H:i:s'),
                'no_approval_temp' => '47432263',
                'dt_approve' => now()->format('Y-m-d H:i:s'),
                'no_approve' => '47432263',
                'no_deal' => $noOrder,
                'at_price' => 77000,
                'no_coupon' => '',
                'no_coupon_approve' => '',
                'at_coupon' => '',
                'no_bonuscard' => '',
                'no_bonuscard_approve' => '',
                'at_bonuscard' => '',
                'no_billkey' => '23070611700218E9',
                'no_card' => '1046886037678605'
            ]
        ]);
    }

    #[OA\Post(
        path: '/infine/mock/cancel',
        summary: '(목업)승인 취소 요청',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Cancel::class))),
        tags: ['infine'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: \App\Responses\Infine\Cancel::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function mockCancel(Cancel $request): JsonResponse
    {
        return response()->json([
            'code' => '0000',
            'message' => '정상처리',
            'data' => [
                'no_order' => $request->noOrder,
                'infine_order' => $request->infineOrder,
                'cancel_type' => $request->cancelType,
                'no_approval' => $request->noApproval,
                'dt_approval_cancel' => now()->format('Y-m-d H:i:s'),
                'result_code' => '0000',
                'result_msg' => '정상처리'
            ]
        ]);
    }

    #[OA\Post(
        path: '/infine/mock/approval',
        summary: '(목업)강제 승인 요청',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Approval::class))),
        tags: ['infine'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: \App\Responses\Infine\Approval::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function mockApproval(Approval $request): JsonResponse
    {
        return response()->json([
            'code' => '0000',
            'message' => '정상처리',
            'data' => [
                'no_order' => $request->noOrder,
                'infine_order' => $request->infineOrder,
                'result_code' => '0000',
                'result_msg' => '정상처리'
            ]
        ]);
    }
}
