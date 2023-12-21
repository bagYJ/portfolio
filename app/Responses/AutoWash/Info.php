<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

use App\Enums\SearchBizKind;
use App\Models\ShopDetail;

class Info
{
    public readonly int $no_shop;
    public readonly ?string $nm_shop;
    public readonly ?string $ds_address;
    public readonly string $ds_tel;
    public readonly ShopDetail $shop_detail;
    public readonly string $yn_open;
    public readonly ?float $at_lat_shop;
    public readonly ?float $at_lng_shop;
    public readonly Partner $partner;
    public readonly string $biz_kind;

    public function __construct(array $response)
    {
        $this->no_shop = data_get($response, 'no_shop');
        $this->nm_shop = sprintf('%s %s', data_get($response, 'partner.nm_partner'), data_get($response, 'nm_shop'));
        $this->ds_address = sprintf('%s $s', data_get($response, 'ds_address'), data_get($response, 'ds_address2'));
        $this->ds_tel = data_get($response, 'ds_tel');
        $this->shop_detail = new ShopDetail(data_get($response, 'shopDetail'));
        $this->yn_open = match (data_get($response, 'shopHolidayExists') || data_get($response, 'shopOptTimeExists')) {
            true => 'N',
            default => match (data_get($response, 'ds_status') == 'N') {
                true => 'N',
                default => match (empty(data_get($response, 'shopDetail.cd_pause_type')) === false) {
                    true => 'E',
                    default => 'Y'
                }
            }
        };
        $this->at_lat_shop = data_get($response, 'at_lat_shop');
        $this->at_lng_shop = data_get($response, 'at_lng_shop');
        $this->partner = new Partner(data_get($response, 'partner'));
        $this->biz_kind = SearchBizKind::getBizKind(data_get($response, 'partner.cd_biz_kind'))->name;
    }
}
