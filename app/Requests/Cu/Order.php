<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use App\Enums\DiscountSale;
use App\Enums\SendType;
use App\Models\OrderList;
use App\Models\RetailOrderProduct;
use App\Models\RetailOrderProductOption;
use App\Utils\Common;
use Illuminate\Support\Collection;
use Owin\OwinCommonUtil\CodeUtil;

class Order
{
    public readonly string $partner_code;
    public readonly string $shop_code;
    public readonly string $no_order;
    public readonly string $nm_order;
    public readonly string $dt_order;
    public readonly string $nm_nick;
    public readonly string $ds_phone;
    public readonly string $ds_car_number;
    public readonly string $dt_pickup;
    public readonly string $ds_pickup_type;
    public readonly ?float $at_delivery;
    public readonly float $ct_order;
    public readonly float $at_price_pg;
    public readonly ?float $at_cpn_disct;
    public readonly ?string $ds_request_msg;
    public readonly ?Collection $list_product;
    public readonly string $trans_dt;
    public readonly string $sign;

    public function __construct(OrderList $order)
    {
        $this->partner_code = getenv('CU_PARTNER_CODE');
        $this->shop_code = $order->shop->store_cd;
        $this->no_order = CodeUtil::convertOrderCodeToCuSpc($order->no_order);
        $this->nm_order = $order->nm_order;
        $this->dt_order = $order->dt_reg->format('YmdHis');
        $this->nm_nick = $order->member->nm_nick;
        $this->ds_phone = $order->ds_safe_number;
        $this->ds_car_number = $order->ds_car_number;
        $this->dt_pickup = $order->dt_pickup->format('YmdHis');
        $this->ds_pickup_type = SendType::tryFrom($order->cd_send_type ?? SendType::DV->value)->name;
        $this->at_delivery = $order->at_send_price;
        $this->ct_order = $order->retailOrderProduct->pluck('ct_inven')->sum();
        $this->at_price_pg = $order->at_price;
        $this->at_cpn_disct = $order->at_cpn_disct;
        $this->ds_request_msg = $order->ds_request_msg;
        $this->list_product = $order->retailOrderProduct->fresh('retailProduct')->map(function (RetailOrderProduct $product) {
            return collect([(new OrderProduct($product))])->push(match ($product->cd_discount_sale) {
                DiscountSale::ONE_PLUS_ONE->value, DiscountSale::TWO_PLUS_ONE->value => $product->retailOrderProductOption->map(function (RetailOrderProductOption $option) use ($product) {
                    return (new OrderProduct($product, $option->at_price_opt > 0));
                }),
                default => null
            })->flatten();
        })->flatten()->filter()->values();
        $this->trans_dt = now()->format('YmdHis');
        $this->sign = Common::getHash(sprintf('%s%s%s%s', $this->partner_code, $this->shop_code, $this->no_order, $this->trans_dt));
    }

    /**
     * @param string $noOrder
     * @return OrderList
     */
    public static function getOrderInfo(string $noOrder): OrderList
    {
        return OrderList::with(['retailOrderProduct.retailOrderProductOption.retailProductOption', 'member', 'shop'])->find($noOrder);
    }
}
