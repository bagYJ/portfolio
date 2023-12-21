<?php

namespace App\Requests\Spc;

use App\Enums\Pickup;
use App\Enums\SpcOrderType;
use App\Models\OrderList;
use Owin\OwinCommonUtil\CodeUtil;

class Order extends Request
{
    public readonly string $brandCode;
    public readonly string $storeCode;
    public readonly string $orderId;
    public readonly string $orderType;
    public readonly string $orderDetailType;
    public readonly string $orderDateTime;
    public readonly string $orderPhoneNo;
    public readonly string $orderCarNo;
    public readonly string $orderMessage;
    public readonly string $requestDate;
    public readonly string $requestTimeFrom;
    public readonly ?string $requestTimeTo;
    public readonly int $orderPrice;
    public readonly array $orderMenus;

    public function __construct(OrderList $order)
    {

        parent::__construct();
        $this->brandCode = $order->partner->cd_spc_brand;
        $this->storeCode = $order->shop->cd_spc_store;
        $this->orderId = CodeUtil::convertOrderCodeToCuSpc($order->no_order);
        $this->orderType = SpcOrderType::tryFrom($order->cd_send_type)->name;
        $this->orderDetailType = match (Pickup::tryFrom($order->cd_pickup_type)) {
            Pickup::RESERVE => 'N',
            default => 'O',
        };
        $this->orderDateTime = $order->dt_reg->format('YmdHis');
        $this->orderPhoneNo = $order->ds_safe_number;
        $this->orderCarNo = $order->ds_car_number ?? "";
        $this->orderMessage = $order->ds_request_msg ?? "";
        $this->requestDate = $order->dt_pickup->format('Ymd');
        $this->requestTimeFrom = match ($this->orderType) {
            SpcOrderType::PICKUP->name => now()->format('Hi'),
            default => $order->dt_pickup->format('Hi')
        };
        $this->requestTimeTo = match ($this->orderType) {
            SpcOrderType::PICKUP->name => null,
            default => $order->dt_pickup->addMinutes(10)->format('Hi')
        };
        $this->orderPrice = $order->at_price;

        $this->orderMenus = $order->orderProduct->fresh('product')->map(function ($product) {
            return collect((new PaymentProduct($product))->setPaymentProduct());
        })->values()->toArray();
    }

    /**
     * @param string $noOrder
     * @return OrderList
     */
    public static function getOrderInfo(string $noOrder): OrderList
    {
        return OrderList::with(['shop', 'partner', 'orderProduct.product'])->find($noOrder);
    }
}