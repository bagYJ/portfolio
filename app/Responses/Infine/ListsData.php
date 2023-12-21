<?php
declare(strict_types=1);

namespace App\Responses\Infine;

use Illuminate\Support\Collection;

class ListsData
{
    public readonly ?int $current_page;
    public readonly ?int $per_page;
    public readonly ?int $last_page;
    public readonly int $total_cnt;
    public readonly Collection $shops;

    public function __construct(array $data)
    {
        $this->current_page = (int)data_get($data, 'current_page');
        $this->per_page = (int)data_get($data, 'per_page');
        $this->last_page = (int)data_get($data, 'last_page');
        $this->total_cnt = (int)data_get($data, 'total_cnt');
        $this->shops = collect(data_get($data, 'shops'))->map(function (array $shop) {
            return new ListsDataShops($shop);
        });
    }
}
