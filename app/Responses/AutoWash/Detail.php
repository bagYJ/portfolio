<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

use App\Enums\SearchBizKindDetail;
use App\Models\OrderList;

class Detail
{
    public readonly string $biz_kind;
    public readonly string $biz_kind_detail;
    public readonly int $no_shop;
    public readonly string $nm_shop;
    public readonly string $no_order_user;

    public function __construct(OrderList $order)
    {
        $this->biz_kind = 'WASH';
        $this->biz_kind_detail = SearchBizKindDetail::getBizKindDetail($order->shop->partner->cd_biz_kind_detail)?->name;
        $this->no_shop = $order->no_shop;
        $this->nm_shop = $order->shop->nm_shop;
        $this->no_order_user = substr($order->no_order, -7);
    }
}
