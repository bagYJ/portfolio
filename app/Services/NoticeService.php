<?php

namespace App\Services;

use App\Models\BbsNotice;
use App\Utils\Common;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NoticeService
{
    /**
     * 공지사항 조회
     * @param int $offset
     * @param int $size
     * @return  LengthAwarePaginator
     */
    public static function gets(int $size, int $offset): LengthAwarePaginator
    {
        return BbsNotice::select([
            'no',
            'ds_title',
            'ds_content',
            'ds_popup_thumb',
            'dt_reg'
        ])->where([
            'yn_show' => 'Y',
            'cd_bbs_target' => '113001'
        ])->orderByDesc('no')->paginate(perPage: $size, page: $offset);
    }

    /**
     * 공지사항 단일 조회
     *
     * @param int $no
     *
     * @return BbsNotice|null
     */
    public static function get(int $no): ?BbsNotice
    {
        $notice = BbsNotice::select([
            'ds_title',
            'ds_content',
            'ds_popup_thumb',
            'dt_reg'
        ])->where([
            'no' => $no,
            'yn_show' => 'Y',
            'cd_bbs_target' => '113001'
        ])->first();

        if ($notice) {
            $notice['ds_popup_thumb'] = Common::getImagePath($notice['ds_popup_thumb']);
            return $notice;
        }
        return null;
    }

    /**
     * 메인 공지사항
     * @return Collection
     */
    public static function getMainNotice(): Collection
    {
        return BbsNotice::where([
            'yn_show' => 'Y',
            'yn_popup' => 'Y',
        ])->whereNotNull('ds_popup_thumb')
            ->orderByDesc('yn_prior_popup')
            ->orderBy('no')->take(10)->get()->map(function ($notice) {
                return [
                    'ds_title' => $notice->ds_title,
                    'ds_content' => $notice->ds_content,
                    'ds_popup_thumb' => Common::getImagePath($notice['ds_popup_thumb']),
                ];
            });
    }
}
