<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class BbsFaq
 *
 * @property int $no
 * @property string|null $id_admin
 * @property string|null $cd_faq_category
 * @property string|null $ds_title
 * @property string|null $ds_content
 * @property string|null $yn_show
 * @property int|null $no_view_order
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $cd_service
 *
 * @package App\Models
 */
class BbsFaq extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    protected $casts = [
        'no_view_order' => 'int'
    ];

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'id_admin',
        'cd_faq_category',
        'ds_title',
        'ds_content',
        'yn_show',
        'no_view_order',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg',
        'cd_service'
    ];
}
