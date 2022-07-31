<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class HttpApiResponse
 *
 * @property int $no
 * @property string|null $msgid
 * @property string|null $to
 * @property string|null $to_country
 * @property string|null $err_code
 * @property string|null $err_code_decript
 * @property string|null $network
 * @property string|null $rescnt
 * @property string|null $sent_date
 * @property string|null $ref
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class HttpApiResponse extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'msgid',
        'to',
        'to_country',
        'err_code',
        'err_code_decript',
        'network',
        'rescnt',
        'sent_date',
        'ref',
        'dt_reg'
    ];
}
