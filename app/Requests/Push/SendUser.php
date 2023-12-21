<?php
declare(strict_types=1);

namespace App\Requests\Push;



use Illuminate\Http\Request;

class SendUser
{
    public readonly array $udids;
    public readonly string $title;
    public readonly string $body;
    public readonly string $bizKind;
    public readonly ?string $bizKindDetail;
    public readonly ?string $status;
    public readonly ?int $noShop;
    public readonly ?string $noOrder;
    public readonly ?string $isOrdering;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));

        $this->udids = data_get($valid, 'udids');
        $this->title = data_get($valid, 'title');
        $this->body = data_get($valid, 'body');
        $this->bizKind = data_get($valid, 'biz_kind');
        $this->bizKindDetail = data_get($valid, 'biz_kind_detail');
        $this->status = data_get($valid, 'status');
        $this->noShop = data_get($valid, 'no_shop');
        $this->noOrder = data_get($valid, 'no_order');
        $this->isOrdering = data_get($valid, 'is_ordering');
    }
}
