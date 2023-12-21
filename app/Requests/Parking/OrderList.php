<?php
declare(strict_types=1);

namespace App\Requests\Parking;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class OrderList
{
    #[OA\Parameter(name: 'page', description: '페이지', in: 'query', schema: new OA\Schema(type: 'integer'))]
    public readonly ?int $page;
    #[OA\Parameter(name: 'userCode', description: '유저식별자', in: 'query', schema: new OA\Schema(type: 'string'))]
    public readonly ?string $userCode;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->page = (int)data_get($valid, 'page');
        $this->userCode = data_get($valid, 'userCode');
    }
}
