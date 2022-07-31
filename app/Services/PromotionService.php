<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MemberDeal;
use App\Models\PromotionDeal;
use App\Models\PromotionOverlap;
use App\Models\PromotionPin;
use Illuminate\Support\Collection;

class PromotionService extends Service
{
    public function pinInfo(string $noPin): Collection
    {
        return PromotionPin::where('no_pin', $noPin)->whereNull('no_user')->whereNotNull('no_deal')->get();
    }

    public static function promotionOverlap(array $parameter): Collection
    {
        return PromotionOverlap::where($parameter)->get();
    }

    public static function memberDealbyNoDeal(array $noUsers, int $noDeal, array $noDeals): Collection
    {
        return MemberDeal::whereIn('no_user', $noUsers)
            ->where(function ($query) use ($noDeal, $noDeals) {
                $query->where('no_deal', $noDeal)
                    ->orWhereNotIn('no_deal', $noDeals);
            })->get();
    }

    public function promotionPin(array $parameter): Collection
    {
        return PromotionPin::where($parameter)->get();
    }

    public function promotionDeal(?array $parameter, ?array $whereNotNull = null): Collection
    {
        return PromotionDeal::where(function ($query) use ($parameter) {
            if (empty($parameter) === false) {
                $query->where($parameter);
            }
        })->where(function ($query) use ($whereNotNull) {
            if (empty($whereNotNull) === false) {
                $query->whereNotNull($whereNotNull);
            }
        })->get();
    }

    public function promotionDealFirst(?array $parameter, ?array $whereNotNull = null): PromotionDeal
    {
        return $this->promotionDeal($parameter, $whereNotNull)->first();
    }

    public function promotionPinUpdate(array $parameter, array $where): void
    {
        PromotionPin::where($where)->update($parameter);
    }
}
