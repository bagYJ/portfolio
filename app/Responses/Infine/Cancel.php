<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class Cancel extends Response
{
    public readonly ?CancelData $data;
    public function __construct(array $response)
    {
        parent::__construct($response);
        $this->data = new CancelData(data_get($response, 'data'));
    }
}
