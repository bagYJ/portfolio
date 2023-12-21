<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

class Car
{
    public readonly int $no;
    public readonly int $no_user;
    public readonly int $seq;
    public readonly string $ds_etc_kind;
    public readonly string $ds_car_number;
    public readonly string $ds_car_color;
    public readonly string $ds_car_search;
    public readonly string $cd_gas_kind;
    public readonly string $ds_chk_rssi_where;
    public readonly int $no_device;
    public readonly string $ds_adver;
    public readonly string $yn_main_car;
    public readonly string $yn_delete;
    public readonly string $ds_sn;
    public readonly string $dt_device_update;

    public function __construct(array $car)
    {
        $this->no = data_get($car, 'no');
        $this->no_user = data_get($car, 'no_user');
        $this->seq = data_get($car, 'seq');
        $this->ds_etc_kind = data_get($car, 'ds_etc_kind');
        $this->ds_car_number = data_get($car, 'ds_car_number');
        $this->ds_car_color = data_get($car, 'ds_car_color');
        $this->ds_car_search = data_get($car, 'ds_car_search');
        $this->cd_gas_kind = data_get($car, 'cd_gas_kind');
        $this->ds_chk_rssi_where = data_get($car, 'ds_chk_rssi_where');
        $this->no_device = data_get($car, 'no_device');
        $this->ds_adver = data_get($car, 'ds_adver');
        $this->yn_main_car = data_get($car, 'yn_main_car');
        $this->yn_delete = data_get($car, 'yn_delete');
        $this->ds_sn = data_get($car, 'ds_sn');
        $this->dt_device_update = data_get($car, 'dt_device_update');
    }
}
