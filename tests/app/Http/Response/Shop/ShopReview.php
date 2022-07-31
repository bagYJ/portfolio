<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Shop;

use OpenApi\Attributes as OA;

#[OA\Schema]
class ShopReview
{
    #[OA\Property(description: '결과')]
    public bool $result;

    #[OA\Property(description: '평점평균')]
    public float $at_grade;
    #[OA\Property(description: '리뷰수')]
    public int $ct_review;
    #[OA\Property(description: '페이지 당 항목 개수')]
    public int $per_page;
    #[OA\Property(description: '현재 페이지번호')]
    public int $current_page;
    #[OA\Property(description: '마지막 페이지 번호')]
    public int $last_page;
    #[OA\Property(description: '리뷰 리스트', type: 'array', items: new OA\Items('#/components/schemas/Review'))]
    public Review $list_review;
}

#[OA\Schema]
class Review
{
    #[OA\Property(description: '관리번호')]
    public int $no;
    #[OA\Property(description: '매장번호')]
    public int $no_shop;
    #[OA\Property(description: '등록회원번호')]
    public int $no_user;

    #[OA\Property(description: '등록닉네임')]
    public string $nm_nick;
    #[OA\Property(description: '등록회원구분')]
    public string $cd_review_auther;

    #[OA\Property(description: '평점')]
    public float $at_grade;
    #[OA\Property(description: '내용')]
    public string $ds_content;

    #[OA\Property(description: '상태')]
    public string $yn_status;
    #[OA\Property(description: '등록아이피')]
    public string $ds_userip;
    #[OA\Property(description: '등록시간')]
    public string $dt_reg;
}
