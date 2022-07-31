<?php

declare(strict_types=1);

namespace App\Request\Cu;

use App\Enums\DiscountSale;
use App\Enums\SendType;
use App\Models\OrderList;
use App\Models\Shop;
use App\Models\User;
use App\Utils\Code;
use App\Utils\Cu;

class Payment
{
    public string $partner_code;
    public string $shop_code;
    public string $no_order;
    public string $nm_order;
    public string $dt_order;
    public string $nm_nick;
    public string $ds_phone;
    public string $ds_car_number;
    public string $dt_pickup;
    public string $ds_pickup_type;
    public ?float $at_delivery;
    public float $ct_order;
    public float $at_price_pg;
    public ?float $at_cpn_disct;
    public ?string $ds_request_msg;
    public ?array $list_product;
    public string $trans_dt;
    public string $sign;

    public function __construct(Shop $shop, User $user, OrderList $orderList)
    {
        $this->partner_code = Code::conf('cu.partner_code');
        $this->shop_code = $shop->store_cd;
        $this->no_order = substr($orderList->no_order, 1);
        $this->nm_order = $orderList->nm_order;
        $this->dt_order = $orderList->dt_reg->format('YmdHis');
        $this->nm_nick = $user->nm_nick;
        $this->ds_phone = $orderList->ds_safe_number;
        $this->ds_car_number = $orderList->ds_car_number;
        $this->dt_pickup = $orderList->dt_pickup->format('YmdHis');
        $this->ds_pickup_type = SendType::tryFrom($orderList->cd_send_type)->name;
        $this->at_delivery = $orderList->at_send_price;
        $this->ct_order = $orderList->retailOrderProduct->pluck('ct_inven')->sum();
        $this->at_price_pg = $orderList->at_price;
        $this->at_cpn_disct = $orderList->at_cpn_disct;
        $this->ds_request_msg = $orderList->ds_request_msg;
        $this->list_product = $orderList->retailOrderProduct->fresh('retailProduct')->map(function ($product) {
            $paymentProduct = (new PaymentProduct($product))->setPaymentProduct();

            $addPaymentProduct = match ($product->cd_discount_sale) {
                DiscountSale::ONE_PLUS_ONE->value, DiscountSale::TWO_PLUS_ONE->value => $product->retailOrderProductOption->map(
                    function ($option) use ($product) {
                        return (new PaymentProduct(
                            $product, ($option->at_price_opt > 0 ? 'N' : 'Y')
                        ))->setPaymentProduct();
                    }
                ),
                default => null
            };

            return collect([$paymentProduct])->push($addPaymentProduct)->flatten();
        })->flatten()->filter()->values()->toArray();
        $this->trans_dt = now()->format('YmdHis');
        $this->sign = Cu::generateSign([$this->partner_code, $this->shop_code, $this->no_order, $this->trans_dt]);
    }

    public function setPayment(): array
    {
        return (array)$this;
    }
}
