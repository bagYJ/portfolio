<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\AppType;
use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RetailSubCategory
 *
 * @property int $no
 * @property int $no_partner
 * @property int $no_category
 * @property int $no_sub_category
 * @property string|null $nm_sub_category
 * @property int|null $at_view
 * @property string|null $ds_status
 * @property string|null $ds_avn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailSubCategory extends Model
{

    use Compoships;

    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no_partner' => 'int',
        'no_category' => 'int',
        'no_sub_category' => 'int',
        'at_view' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no_partner',
        'no_category',
        'no_sub_category',
        'nm_sub_category',
        'at_view',
        'ds_status',
        'ds_avn_status',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg'
    ];

    protected static function booted()
    {
        static::addGlobalScope('ds_status', function (Builder $builder) {
            $builder->where((getAppType() == AppType::AVN ? 'ds_avn_status' : 'ds_status'), 'Y')->orderBy('at_view');
        });
    }

    public function retailCategory(): BelongsTo
    {
        return $this->belongsTo(RetailCategory::class, ['no_partner', 'no_category'], ['no_partner', 'no_category']);
    }
}
