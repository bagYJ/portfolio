<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\AppType;
use Awobaz\Compoships\Compoships;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class RetailCategory
 *
 * @property int $no
 * @property int $no_partner
 * @property int $no_category
 * @property string|null $nm_category
 * @property Carbon|null $dt_use_st
 * @property Carbon|null $dt_use_end
 * @property string|null $yn_top
 * @property int|null $at_view
 * @property string|null $yn_show
 * @property string|null $ds_status
 * @property string|null $ds_avn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailCategory extends Model
{
    use Compoships;

    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_partner' => 'int',
        'no_category' => 'int',
        'at_view' => 'int'
    ];

    protected $dates = [
        'dt_use_st',
        'dt_use_end',
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_partner',
        'no_category',
        'nm_category',
        'dt_use_st',
        'dt_use_end',
        'yn_top',
        'at_view',
        'yn_show',
        'ds_status',
        'ds_avn_status',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];

    public function retailSubCategories(): HasMany
    {
        return $this->hasMany(RetailSubCategory::class, ['no_partner', 'no_category'], ['no_partner', 'no_category']);
    }
}
