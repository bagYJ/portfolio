<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BbsEvent;
use App\Models\BbsFaq;
use App\Utils\Common;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomerService extends Service
{
    public function getEventList(
        array $eventTarget,
        ?int $offset = null,
        ?int $page = null,
        ?string $status = 'Y'
    ): Collection {
        $bbsEvent = BbsEvent::with('shop.partner')->where([
            'yn_show' => 'Y',
            'cd_service' => '900100'
        ])->whereIn('cd_event_target', $eventTarget)->when(empty($status) === false, function ($query) use ($status) {
            $today = now()->format('Y-m-d');
            match ($status) {
                'Y' => $query->whereBetween(DB::raw("'{$today}'"), [DB::raw('dt_start'), DB::raw('dt_end')]),
                default => $query->whereNotNull('dt_end')->where('dt_end', '<', now()->format('Y-m-d'))
            };
        })->orderByDesc('no');

        if (isset($page) && isset($offset)) {
            $bbsEvent = $bbsEvent->forPage($page, $offset);
        }

        return $bbsEvent->get()->map(function ($event) {
            return [
                'no' => $event->no,
                'ds_title' => $event->ds_title,
                'ds_content' => $event->ds_content,
                'ds_thumb' => $event->ds_thumb,
                'dt_start' => $event->dt_start?->format('Y-m-d'),
                'dt_end' => $event->dt_end?->format('Y-m-d'),
                'yn_move_button' => $event->yn_move_button,
                'dt_event_start' => $event->dt_event_start,
                'ds_detail_url' => Common::getImagePath($event->ds_detail_url),
                'link_act' => $event->link_act,
                'no_shop' => $event->no_shop,
                'no_product' => $event->no_product,
                'cd_biz_kind' => $event->shop?->partner?->cd_biz_kind,
            ];
        });
    }

    public static function getEvent(int $no): Collection
    {
        $bbsEvent = BbsEvent::where(['no' => $no, 'yn_show' => 'Y', 'cd_service' => '900100'])->whereBetween(DB::raw('now()'), [DB::raw('dt_start'), DB::raw('dt_end')])
            ->orderByDesc('no');

        return $bbsEvent->get()->map(function ($event) {
            return [
                'no' => $event->no,
                'ds_thumb' => $event->ds_thumb,
                'ds_popup_thumb' => $event->ds_popup_thumb,
                'dt_reg' => $event->dt_reg
            ];
        });
    }

    public static function getFaqList(array $select, array $where, int $size, int $offset): LengthAwarePaginator
    {
        return BbsFaq::select($select)->where($where)->orderBy('no_view_order')->paginate(perPage: $size, page: $offset);
    }
}
