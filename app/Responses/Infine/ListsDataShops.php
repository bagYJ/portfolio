<?php
declare(strict_types=1);

namespace App\Responses\Infine;

use Illuminate\Support\Collection;

class ListsDataShops
{
    public readonly string $ds_uni;
    public readonly string $ds_poll_div;
    public readonly string $nm_shop;
    public readonly ?string $ds_van_adr;
    public readonly string $ds_new_adr;
    public readonly string $ds_tel;
    public readonly ?float $ds_gis_x;
    public readonly ?float $ds_gis_y;
    public readonly string $ds_open_time;
    public readonly string $ds_close_time;
    public readonly ?float $at_lat;
    public readonly ?float $at_lng;
    public readonly ?string $yn_maint;
    public readonly ?string $yn_cvs;
    public readonly ?string $yn_car_wash;
    public readonly string $yn_self;
    public readonly Collection $arks;
    public readonly Collection $prices;

    public function __construct(array $shops)
    {
        $this->ds_uni = data_get($shops, 'ds_uni');
        $this->ds_poll_div = data_get($shops, 'ds_poll_div');
        $this->nm_shop = data_get($shops, 'nm_shop');
        $this->ds_van_adr = data_get($shops, 'ds_van_adr');
        $this->ds_new_adr = data_get($shops, 'ds_new_adr');
        $this->ds_tel = data_get($shops, 'ds_tel');
        $this->ds_gis_x = (float)data_get($shops, 'ds_gis_x');
        $this->ds_gis_y = (float)data_get($shops, 'ds_gis_y');
        $this->ds_open_time = data_get($shops, 'ds_open_time');
        $this->ds_close_time = data_get($shops, 'ds_close_time');
        $this->at_lat = (float)data_get($shops, 'at_lat');
        $this->at_lng = (float)data_get($shops, 'at_lng');
        $this->yn_maint = data_get($shops, 'yn_maint');
        $this->yn_cvs = data_get($shops, 'yn_cvs');
        $this->yn_car_wash = data_get($shops, 'yn_car_wash');
        $this->yn_self = data_get($shops, 'yn_self');
        $this->arks = collect(data_get($shops, 'arks'))->map(function (array $ark) {
            return new ListsDataShopsArks($ark);
        });
        $this->prices = collect(data_get($shops, 'prices'))->map(function (array $price) {
            return new ListsDataShopsPrices($price);
        });
    }
}
