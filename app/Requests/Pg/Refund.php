<?php
declare(strict_types=1);

namespace App\Requests\Pg;

use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Refund
{
    public readonly string $nmPg;
    public readonly string $dsResOrderNo;
    public readonly int $price;
    public readonly string $reason;
    public readonly ?string $noOrder;
    public readonly ?string $dsServerReg;
    public readonly ?string $nmOrder;

    /**
     * @param Request $request
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->nmPg = strtoupper(data_get($valid, 'nmPg'));
        $this->dsResOrderNo = data_get($valid, 'dsResOrderNo');
        $this->price = (int)data_get($valid, 'price');
        $this->reason = data_get($valid, 'reason');
        $this->noOrder = data_get($valid, 'noOrder');
        $this->dsServerReg = data_get($valid, 'dsServerReg');
        $this->nmOrder = data_get($valid, 'nmOrder');
    }
}
