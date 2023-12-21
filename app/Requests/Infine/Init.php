<?php
declare(strict_types=1);

namespace App\Requests\Infine;

use App\Models\OrderList;

class Init
{
    public readonly string $noOrder;
    public readonly string $carNumber;
    public readonly string $dsUni;
    public readonly string $cdGasKind;
//    public readonly string $order_type;
    public readonly float $atPrice;
    public readonly float $atLiterGas;
    public readonly int $noCard;
    public readonly string $dsBillkey;
    public readonly array $noNozzle;
    public readonly ?int $noCoupon;
    public readonly ?int $atCouponDisct;
    public readonly ?int $noPointcard;
    public readonly ?int $atPointDisct;

    public function __construct(OrderList $order)
    {
        $this->noOrder = $order->no_order;
        $this->carNumber = $order->ds_car_number;
        $this->dsUni = $order->shopOil->ds_uni;
        $this->cdGasKind = $order->cd_gas_kind;
//        $this->order_type = $order->no_order;
        $this->atPrice = $order->at_price;
        $this->atLiterGas = $order->at_liter_gas;
        $this->noCard = $order->no_card;
        $this->dsBillkey = $order->card->where('cd_pg', '500100')->first()->ds_billkey;
        $this->noNozzle = $order->shop->oilUnits->pluck('ds_unit_id')->all();
        $this->noCoupon = $order->coupon?->no_coupon;
        $this->atCouponDisct = $order->at_cpn_disct;
        $this->noPointcard = $order->id_pointcard;
        $this->atPointDisct = $order->at_point_disct;
    }
}
