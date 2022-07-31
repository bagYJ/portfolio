<?php

namespace App\Http\Controllers;

use App\Enums\SearchBizKind;
use App\Exceptions\OwinException;
use App\Services\DirectOrderService;
use App\Services\OrderService;
use App\Utils\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;

class DirectOrder extends Controller
{
    public static function list(): JsonResponse
    {
        $rows = DirectOrderService::get([
            'no_user' => Auth::id()
        ]);

        return response()->json([
            'result' => true,
            'count' => $rows->count(),
            'rows' => $rows,
        ]);
    }

    public static function create(Request $request): JsonResponse
    {
        $request->validate([
            'no_order' => 'required',
            'biz_kind' => ['required', Rule::in(SearchBizKind::keys())]
        ]);

        $cdBizKind = match ($request->biz_kind) {
            SearchBizKind::PARKING->name => Code::conf('biz_kind.parking'),
            SearchBizKind::WASH->name => Code::conf('biz_kind.wash'),
            default => OrderService::getOrder($request->no_order)?->partner->cd_biz_kind
        };

        if (empty($cdBizKind)) {
            throw new OwinException(Code::message('P2120'));
        }

        DirectOrderService::get([
            'no_user' => Auth::id(),
            'cd_biz_kind' => $cdBizKind,
            'no_order' => $request->no_order
        ])->whenNotEmpty(function () {
            throw new OwinException(Code::message('P2121'));
        }, function () use ($request, $cdBizKind) {
            DirectOrderService::create(Auth::id(), $request->no_order, $cdBizKind);
        });

        return response()->json([
            'result' => true
        ]);
    }

    public static function remove(int $no): JsonResponse
    {
        DirectOrderService::remove(Auth::id(), $no);

        return response()->json([
            'result' => true,
        ]);
    }
}
