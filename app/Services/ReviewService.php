<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShopReview;
use App\Models\ShopReviewSiren;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReviewService extends Service
{
    /**
     * 상점별 리뷰 총점
     * @param int $noShop
     * @return Model|null
     */
    public static function getReviewTotal(int $noShop): ?Model
    {
        return self::getShopReview([$noShop])->first();
    }

    public static function getShopReview(array $noShops): Collection
    {
        return ShopReview::select([
            'no_shop',
            DB::raw("COUNT(no) AS ct_review"),
            DB::raw("AVG(at_grade) AS at_grade")
        ])->whereIn('no_shop', $noShops)
            ->where('yn_status', 'Y')->groupBy('no_shop')->get();
    }

    /**
     * 리뷰 리스트
     * @param int $noShop
     * @param int $offset
     * @param int $size
     * @return LengthAwarePaginator
     */
    public static function getReviews(int $noShop, int $offset = 0, int $size = 12): LengthAwarePaginator
    {
        return ShopReview::where([
            'no_shop' => $noShop
        ])->orderByDesc('dt_reg')->paginate(
            perPage: $size,
            page: $offset
        );
    }

    /**
     * 리뷰 등록
     * @param array $data
     * @return void
     */
    public static function create(array $data): void
    {
        ShopReview::create($data);
    }

    public static function getReview(array $parameter): Collection
    {
        return ShopReview::where($parameter)->get();
    }

    public static function remove(int $noUser, int $no): bool
    {
        return (bool)ShopReview::where(['no_user' => $noUser, 'no' => $no])->delete();
    }

    public static function siren(array $parameter): void
    {
        ShopReviewSiren::create($parameter);
    }

    public static function getReviewSiren(array $parameter): Collection
    {
        return ShopReviewSiren::where($parameter)->get();
    }
}
