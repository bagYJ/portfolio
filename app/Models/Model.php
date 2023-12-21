<?php
declare(strict_types=1);

namespace App\Models;

use App\Traits\DateTimeSerializable;
use App\Traits\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @mixin Builder
 * @
 *
 */
class Model extends EloquentModel
{
    use DateTimeSerializable;
    use Table;
//    use SoftDeletes;
}
