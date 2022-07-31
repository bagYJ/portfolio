<?php

declare(strict_types=1);

namespace Tests\app\Http\Response\Card;

use OpenApi\Attributes as OA;

#[OA\Schema]
class CardListResponse
{
    #[OA\Property(description: '성공 여부')]
    public bool $result;
    #[OA\Property(description: '카드정보', type: 'array', items: new OA\Items(ref: '#/components/schemas/CardList'))]
    public CardList $card_list;
}

#[OA\Schema]
class CardList
{
    #[OA\Property(description: '카드번호')]
    public int $no_card;
    #[OA\Property(description: '사용자카드번호(카드번호뒤4자리)')]
    public string $no_card_user;
    #[OA\Property(description: '카드회사코드')]
    public string $cd_card_corp;
    #[OA\Property(description: '카드회사명')]
    public string $card_corp;
    #[OA\Property(description: '메인카드여부')]
    public string $yn_main_card;
}
