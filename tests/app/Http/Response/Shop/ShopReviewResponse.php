<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Shop;

use OpenApi\Attributes as OA;

#[OA\Schema]
class ShopReviewResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '리뷰평점', type: 'array', items: new OA\Items('#/components/schemas/ReviewResponse'))]
    public ReviewResponse $review;
}

#[OA\Schema]
class ReviewResponse
{
    #[OA\Property(description: '상점번호')]
    public int $no_shop;
    #[OA\Property(description: '리뷰 갯수')]
    public int $ct_review;
    #[OA\Property(description: '리뷰 평점')]
    public float $at_grade;
}
