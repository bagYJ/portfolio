<?php

declare(strict_types=1);

namespace App\Interface;

interface PgInterface
{
    public function request(array $request): array;

    public function payment(array $request): array;

    public function refund(array $orderList, string $reason): array;
}
