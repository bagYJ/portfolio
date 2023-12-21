<?php
declare(strict_types=1);

namespace App\Requests\Pg;

use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Payment
{
    public readonly string $nmPg;
    public readonly string $noOrder;
    public readonly ?int $noShop;
    public readonly int $noUser;
    public readonly string $nmBuyer;
    public readonly string $email;
    public readonly string $phone;
    public readonly int $price;
    public readonly int $atCupDeposit;
    public readonly string $billkey;
    public readonly string $nmOrder;

    /**
     * @param Request $request
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->nmPg = strtoupper(data_get($valid, 'nmPg'));
        $this->noOrder = data_get($valid, 'noOrder');
        $this->noShop = (int)data_get($valid, 'noShop');
        $this->noUser = (int)data_get($valid, 'noUser');
        $this->nmBuyer = data_get($valid, 'nmBuyer');
        $this->email = data_get($valid, 'email');
        $this->phone = data_get($valid, 'phone');
        $this->price = (int)data_get($valid, 'price');
        $this->atCupDeposit = (int)data_get($valid, 'atCupDeposit') ?? 0;
        $this->billkey = data_get($valid, 'billkey');
        $this->nmOrder = data_get($valid, 'nmOrder');
    }
}
