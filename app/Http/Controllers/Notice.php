<?php

namespace App\Http\Controllers;

use App\Services\NoticeService;
use App\Utils\Code;
use App\Utils\Common;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Notice extends Controller
{
    public function gets(Request $request): JsonResponse
    {
        $request->validate([
            'size' => 'nullable|integer|min:1',
            'offset' => 'nullable|integer|min:0',
        ]);

        $size = (int)$request->get('size') ?: Code::conf('default_size');
        $offset = (int)$request->get('offset') ?: 0;

        $notices = NoticeService::gets($size, $offset);

        if (count($notices)) {
            return response()->json([
                'result' => true,
                'total_cnt' => $notices->total(),
                'per_page' => $notices->perPage(),
                'current_page' => $notices->currentPage(),
                'last_page' => $notices->lastPage(),
                'rows' => $notices->map(function ($notice) {
                    return [
                        'no' => $notice->no,
                        'ds_title' => $notice->ds_title,
                        'ds_content' => $notice->ds_content,
                        'ds_popup_thumb' => Common::getImagePath($notice['ds_popup_thumb']),
                        'dt_reg' => $notice->dt_reg->format('Y-m-d H:i:s')
                    ];
                })
            ]);
        } else {
            return response()->json([], 404);
        }
    }

    public function get(int $no): JsonResponse
    {
        $notice = NoticeService::get($no);

        if ($notice) {
            return response()->json([
                'result' => true,
                'notice' => $notice
            ]);
        } else {
            return response()->json([], 404);
        }
    }
}
