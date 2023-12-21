<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enum;

enum Method
{
    use Enum;

    case GET;
    case POST;
    case PUT;
    case DELETE;
    case OPTIONS;
    case PATCH;
}
