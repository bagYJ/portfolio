<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PartnerCategory
 *
 * @property int $no
 * @property int $no_partner_category
 * @property string|null $no_category
 * @property int|null $no_partner
 * @property string|null $nm_category
 * @property int|null $ct_order
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string $yn_commission
 *
 * @package App\Models
 */
class PartnerCategory extends Model
{
    protected $primaryKey = 'no_partner_category';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_partner_category' => 'int',
        'no_partner' => 'int',
        'ct_order' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no',
        'no_category',
        'no_partner',
        'nm_category',
        'ct_order',
        'dt_reg',
        'dt_upt',
        'yn_commission'
    ];
}
