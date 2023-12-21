<?php
declare(strict_types=1);

namespace App\Requests\Pg;

use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Regist
{
    public readonly string $noOrder;
    public readonly string $nmOrder;
    public readonly int $price;
    public readonly string $nmBuyer;
    public readonly string $phone;
    public readonly string $email;

    public readonly string $cardNum;
    public readonly string $expYear;
    public readonly string $expMon;
    public readonly ?string $noBiz;
    public readonly ?string $birthday;
    public readonly string $noPin;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->noOrder = data_get($valid, 'noOrder');
        $this->nmOrder = data_get($valid, 'nmOrder');
        $this->price = 0;
        $this->nmBuyer = data_get($valid, 'nmBuyer');
        $this->phone = data_get($valid, 'phone');
        $this->email = data_get($valid, 'email');
        $this->cardNum = data_get($valid, 'cardNum');
        $this->expYear = data_get($valid, 'expYear');
        $this->expMon = data_get($valid, 'expMon');
        $this->noBiz = data_get($valid, 'noBiz');
        $this->birthday = data_get($valid, 'birthday');
        $this->noPin = data_get($valid, 'noPin');
    }
}
