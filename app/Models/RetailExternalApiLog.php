<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class RetailExternalApiLog
 *
 * @property int $no
 * @property string $api_url
 * @property string|null $call_path
 * @property Carbon $dt_request
 * @property string|null $result_code
 * @property string|null $result_msg
 * @property string|null $request_param
 * @property string|null $ori_request
 * @property string|null $response_param
 * @property string|null $ori_response
 * @property string|null $no_order
 *
 * @package App\Models
 */
class RetailExternalApiLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $dates = [
        'dt_request'
    ];

    protected $fillable = [
        'api_url',
        'call_path',
        'dt_request',
        'result_code',
        'result_msg',
        'request_param',
        'ori_request',
        'response_param',
        'ori_response',
        'no_order'
    ];
}
