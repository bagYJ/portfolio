<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EnumYN;
use App\Services\CustomerService;
use App\Services\MemberService;
use App\Utils\Code;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Customer extends Controller
{
    public function getEventList(Request $request): JsonResponse
    {
        $request->validate([
            'size' => 'nullable|integer|min:1',
            'offset' => 'nullable|integer|min:0',
            'status' => 'nullable|string'
        ]);

        $size = (int)$request->get('size') ?: Code::conf('default_size');
        $offset = (int)$request->get('offset') ?: 0;

        return response()->json([
            'result' => true,
            'event_list' => (new CustomerService())->getEventList(
                ['331100'] + [Auth::user()?->memberApt?->aptList?->cd_event_target],
                $size,
                $offset,
                $request->status
            )
        ]);
    }

    public function getEvent(int $no): JsonResponse
    {
        return response()->Json([
            'result' => true,
            'event' => CustomerService::getEvent($no)
        ]);
    }

    public function getFaqList(Request $request): JsonResponse
    {
        $request->validate([
            'size' => 'nullable|integer|min:1',
            'offset' => 'nullable|integer|min:0'
        ]);
        $size = (int)$request->get('size') ?: Code::conf('default_size');
        $offset = (int)$request->get('offset') ?: 0;
        $items = CustomerService::getFaqList([
            'ds_title',
            'ds_content',
        ], [
            'yn_show' => 'Y',
            'cd_service' => '900100'
        ], $size, $offset);

        return response()->Json([
            'result' => true,
            'total_cnt' => $items->total(),
            'per_page' => $items->perPage(),
            'current_page' => $items->currentPage(),
            'last_page' => $items->lastPage(),
            'faq' => $items->items()
        ]);
    }

    public function modifyEventPushYn(Request $request): JsonResponse
    {
        $request->validate(['yn_push_msg_event' => ['required', Rule::in(array_column(EnumYN::cases(), 'name'))]]);
        MemberService::updateEventPushYn($request->get('yn_push_msg_event'));
        return response()->json([
            'result' => true
        ]);
    }

    public function getEventPush(): JsonResponse
    {
        return response()->json([
            'result' => true,
            'is_push_msg_event' => Auth::user()->yn_push_msg_event == 'Y'
        ]);
    }
}
