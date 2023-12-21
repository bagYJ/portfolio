<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RetailAdminChkLog
 * 
 * @property int $seq
 * @property string $no_order
 * @property int $no_user
 * @property int $no_shop
 * @property string $log_type
 * @property string|null $content
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 * @property int|null $id_admin
 *
 * @package App\Models
 */
class RetailAdminChkLog extends Model
{
	protected $primaryKey = 'seq';
	public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int',
        'dt_reg' => 'date',
        'dt_upt' => 'date',
        'id_admin' => 'int'
    ];

	protected $fillable = [
		'no_order',
		'no_user',
		'no_shop',
		'log_type',
		'content',
		'dt_reg',
		'dt_upt',
		'id_admin'
	];
}
