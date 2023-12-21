<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

class Intro
{
    public readonly string $no_order;
    public readonly Car $cars;
    public readonly Card $cards;
    public readonly Coupon $coupons;
    public readonly WashProduct $wash_products;
    public readonly Benefit $benefit;

    public function __construct(array $response)
    {
        $this->no_order = data_get($response, 'no_order');
        $this->cars = new Car(data_get($response, 'cars'));
        $this->cards = new Card(data_get($response, 'cards'));
        $this->coupons = new Coupon(data_get($response, 'coupons'));
        $this->wash_products = new WashProduct(data_get($response, 'wash_products'));
        $this->benefit = new Benefit(data_get($response, 'benefit'));
    }
}
