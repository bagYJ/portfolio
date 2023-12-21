<?php
declare(strict_types=1);

namespace App\Requests\Parking;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'request.parking.OrderSearch', description: '')]
class OrderSearch
{
    #[OA\Property(description: '구매번호 Array. 최대 10개', items: new OA\Items(type: 'integer'))]
    public readonly array $uids;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->uids = data_get($valid, 'uids');
    }
}
