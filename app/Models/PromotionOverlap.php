<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PromotionOverlap
 *
 * @property int $no
 * @property int $no_basis_seq
 * @property int $no_overlap_seq
 * @property string|null $ds_type
 * @property string|null $ds_status
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 *
 * @package App\Models
 */
class PromotionOverlap extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no_basis_seq' => 'int',
        'no_overlap_seq' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_del'
    ];

    protected $fillable = [
        'no_basis_seq',
        'no_overlap_seq',
        'ds_type',
        'ds_status',
        'id_reg',
        'dt_reg',
        'id_del',
        'dt_del'
    ];
}
