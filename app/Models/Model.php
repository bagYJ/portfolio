<?php

namespace App\Models;

use App\Traits\DateTimeSerializable;
use App\Traits\Table;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class Model extends EloquentModel
{
    use DateTimeSerializable;
    use Table;
}
