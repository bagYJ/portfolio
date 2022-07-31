<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Product;

use OpenApi\Attributes as OA;

#[OA\Schema(description: '상품 정보')]
class Product
{

    #[OA\Property(description: '상품번호', example: 10921001)]
    public int $no_product;
    #[OA\Property(description: '선택옵션 그룹번호', example: '')]
    public string $ds_option_sel;
    #[OA\Property(description: '상품명', example: '아메리카노')]
    public string $nm_product;
    #[OA\Property(description: '상품정보', example: '커피')]
    public string $ds_content;
    #[OA\Property(description: '카테고리 번호', example: 10921000)]
    public int $no_partner_category;
    #[OA\Property(description: '할인전 금액', example: 3000)]
    public int $at_price_before;
    #[OA\Property(description: '판매가', example: 2800)]
    public int $at_price;
    #[OA\Property(description: '상품 이미지경로', example: '/data2/partner/default.png')]
    public string $ds_image_path;
    #[OA\Property(description: '신제품 여부', example: 'Y')]
    public string $yn_new;
    #[OA\Property(description: '추천상품 여부', example: 'N')]
    public string $yn_vote;
    #[OA\Property(description: '상품정렬순서', example: 1)]
    public int $at_view_order;
    #[OA\Property(description: '할인율', example: 0)]
    public int $at_ratio;
    #[OA\Property(description: '교환/반품 안내')]
    public string $policy_uri;
    #[OA\Property(type: 'array', items: new OA\Items(ref: '#/components/schemas/OptionGroup'))]
    public OptionGroup $option_groups;
}

#[OA\Schema(description: '옵션그룹')]
class OptionGroup
{
    #[OA\Property(description: '옵션그룹 번호', example: '1092100')]
    public int $no_group;
    #[OA\Property(description: '옵션그룹명', example: 'HOT/ICE')]
    public string $nm_group;
    #[OA\Property(description: '옵션최소선택갯수', example: 0)]
    public int $min_option_select;
    #[OA\Property(description: '옵션최대선택갯수', example: 10)]
    public int $max_option_select;
    #[OA\Property(description: '옵션선택타입 (checkbox / radio)', example: 'checkbox')]
    public int $option_type;
    #[OA\Property(ref: '#/components/schemas/OptionGroup')]
    public Option $product_options;
}

#[OA\Schema(description: '옵션')]
class Option
{
    #[OA\Property(description: '옵션번호', example: 1092100100)]
    public int $no_option;
    #[OA\Property(description: '옵션명', example: 'HOT')]
    public int $nm_option;
    #[OA\Property(description: '옵션추가금액', example: 500)]
    public int $at_add_price;
}
