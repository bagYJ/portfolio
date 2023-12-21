<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class Lists extends Response
{
    public readonly ?ListsData $data;
    public function __construct(array $response)
    {
        parent::__construct($response);
        $this->data = new ListsData(data_get($response, 'data'));
    }
}
