<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class GsSaleCardIssueLog
 *
 * @property int $no
 * @property string $id_pointcard
 * @property int $no_user
 * @property string $ds_issue_status
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class GsSaleCardIssueLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'id_pointcard',
        'no_user',
        'ds_issue_status',
        'dt_reg'
    ];
}
