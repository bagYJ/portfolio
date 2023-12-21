<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class Init extends Response
{
    public readonly ?InitData $data;
    public function __construct(array $response)
    {
        parent::__construct($response);
        $this->data = new InitData(data_get($response, 'data'));
    }
}
