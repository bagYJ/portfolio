<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Retail;

use OpenApi\Attributes as OA;

#[OA\Schema]
class RetailProductInfoResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '상품번호')]
    public int $no_product;
    #[OA\Property(description: '브랜드번호')]
    public int $no_partner;
    #[OA\Property(description: '카테고리번호')]
    public int $no_category;
    #[OA\Property(description: '서브카테고리번호')]
    public ?int $no_sub_category;
    #[OA\Property(description: '상품명')]
    public string $nm_product;
    #[OA\Property(description: '상품정보')]
    public string $ds_content;
    #[OA\Property(description: '할인전 상품금액')]
    public int $at_price_before;
    #[OA\Property(description: '상품금액')]
    public int $at_price;
    #[OA\Property(description: '상품이미지')]
    public string $ds_image_path;
    #[OA\Property(description: '상품상세이미지')]
    public string $ds_detail_image_path;
    #[OA\Property(description: '상품 재고수량')]
    public int $cnt_product;
    #[OA\Property(description: '상품 판매 타입 (DISCOUNT: 금액할인, ONE_PLUS_ONE: 1+1, TWO_PLUS_ONE: 2+1, GIFT: 단품증정, SET: 세트상품, TWO_PLUS_TWO: 2+2)')]
    public string $cd_discount_sale;
    #[OA\Property(description: '옵션여부')]
    public string $yn_option;
    #[OA\Property(description: '신상품여부')]
    public string $yn_new;
    #[OA\Property(description: '품절여부')]
    public string $yn_soldout;
    #[OA\Property(description: '부분품절여부')]
    public string $yn_part_soldout;
    #[OA\Property(description: '교환/반품 안내')]
    public string $policy_uri;
    #[OA\Property(description: '옵션그룹', type: 'array', items: new OA\Items('#/components/schemas/ProductOptionGroups'))]
    public ProductOptionGroups $product_option_groups;
    #[OA\Property(description: '2+1 상품', type: 'array', items: new OA\Items('#/components/schemas/TwoPlusOneOption'))]
    public TwoPlusOneOption $two_plus_one_option;
}

#[OA\Schema]
class ProductOptionGroups
{
    #[OA\Property(description: '옵션그룹번호')]
    public int $no_group;
    #[OA\Property(description: '옵션그룹명')]
    public string $nm_group;
    #[OA\Property(description: '옵션 타입 (REQUIRED: 필수, SELECT: 선택, OVERLAP: 중복)')]
    public string $cd_option_type;
    #[OA\Property(description: '최소 선택갯수')]
    public int $at_select_min;
    #[OA\Property(description: '최대 선택갯수')]
    public int $at_select_max;
    #[OA\Property(description: '옵션', type: 'array', items: new OA\Items('#/components/schemas/ProductOptionProducts'))]
    public ProductOptionProducts $product_option_products;
}

#[OA\Schema]
class ProductOptionProducts
{
    #[OA\Property(description: '옵션번호')]
    public int $no_option;
    #[OA\Property(description: '옵션명')]
    public string $nm_option;
    #[OA\Property(description: '옵션 재고수량')]
    public int $cnt_product;
    #[OA\Property(description: '옵션 금액')]
    public int $at_add_price;
}

#[OA\Schema]
class TwoPlusOneOption
{
    #[OA\Property(description: '상품명')]
    public string $nm_product;
    #[OA\Property(description: '2+1 상품타입 (SINGLE: 단품, DOUBLE: 2+1)')]
    public string $discount_type;
    #[OA\Property(description: '상품금액')]
    public int $at_price;
}
