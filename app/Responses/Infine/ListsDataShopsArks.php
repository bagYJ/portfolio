<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class ListsDataShopsArks
{
    public readonly string $unit_id;

    public function __construct(array $ark)
    {
        $this->unit_id = data_get($ark, 'unit_id');
    }
}
