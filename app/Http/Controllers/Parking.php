<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Requests\Parking\{Order, OrderList, OrderSearch};
use App\Responses\Parking\{Booking,
    Cancel,
    Order as OrderResponse,
    OrderList as OrderListResponse,
    OrderSearch as OrderSearchResponse,
    Site};
use App\Utils\Common;
use Exception;
use Illuminate\{Auth\Access\AuthorizationException, Http\JsonResponse};
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'parking', description: '주차')]
class Parking extends Controller
{
    private readonly \App\Services\Dev\Parking|\App\Services\Production\Parking $service;

    public function __construct()
    {
        $this->service = Common::getService('Parking');
    }

    /**
     * @param Order $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/parking/bookings',
        summary: '주차장 할인권 구매',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(Order::class))),
        tags: ['parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: OrderResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function order(Order $request): JsonResponse
    {
        $response = $this->service::order($request);
        return response()->json(new OrderResponse($response));
    }

    /**
     * @param OrderList $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Get(
        path: '/parking/bookings',
        summary: '주차장 할인권 구매정보 내역',
        tags: ['parking'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/page'),
            new OA\Parameter(ref: '#/components/parameters/userCode')
        ], responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: OrderListResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function orderList(OrderList $request): JsonResponse
    {
        $response = $this->service::orderList($request);
        return response()->json(new OrderListResponse($response));
    }

    /**
     * @param OrderSearch $request
     * @return JsonResponse
     * @throws Exception
     */
    #[OA\Post(
        path: '/parking/bookings/search',
        summary: '주차장 할인권 구매내역(uids)',
        requestBody: new OA\RequestBody(content: new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(OrderSearch::class))),
        tags: ['parking'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: OrderSearchResponse::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function orderSearch(OrderSearch $request): JsonResponse
    {
        $response = $this->service::orderSearch($request);
        return response()->json(new OrderSearchResponse($response));
    }

    /**
     * @param string $bookingUid
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Get(
        path: '/parking/bookings/{bookingUid}',
        summary: '주차장 할인권 구매정보 조회',
        tags: ['parking'],
        parameters: [
            new OA\Parameter(name: 'bookingUid', description: '구매번호', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Booking::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function orderDetail(string $bookingUid): JsonResponse
    {
        $response = $this->service::orderDetail($bookingUid);
        return response()->json(new Booking($response));
    }

    /**
     * @param int $bookingUid
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Put(
        path: '/parking/bookings/{bookingUid}/cancel',
        summary: '주차장 할인권 구매정보 조회',
        tags: ['parking'],
        parameters: [
            new OA\Parameter(name: 'bookingUid', description: '구매번호', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Cancel::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function cancel(int $bookingUid): JsonResponse
    {
        $response = $this->service::cancel($bookingUid);
        return response()->json(new Cancel($response));
    }

    /**
     * @param string $bookingUid
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Put(
        path: '/parking/bookings/{bookingUid}/used',
        summary: '주차장 할인권 임시사용',
        tags: ['parking'],
        parameters: [
            new OA\Parameter(name: 'bookingUid', description: '구매번호', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Booking::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function used(string $bookingUid): JsonResponse
    {
        $response = $this->service::used($bookingUid);
        return response()->json(new Booking($response));
    }

    /**
     * @param string $siteUid
     * @return JsonResponse
     * @throws AuthorizationException
     */
    #[OA\Get(
        path: '/parking/{siteUid}',
        summary: '주차장 정보 조회',
        tags: ['parking'],
        parameters: [
            new OA\Parameter(name: 'siteUid', description: '주차장 고유번호', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(ref: Site::class)),
            new OA\Response(ref: '#/components/responses/Exception', response: 'default')
        ]
    )]
    public function site(string $siteUid): JsonResponse
    {
        $response = $this->service::site($siteUid);
        return response()->json(new Site($response));
    }
}
