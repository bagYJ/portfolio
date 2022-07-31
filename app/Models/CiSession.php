<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class CiSession
 *
 * @property int $no
 * @property string $id
 * @property string $ip_address
 * @property int $save_timestamp
 * @property string $data
 *
 * @package App\Models
 */
class CiSession extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $casts = [
        'save_timestamp' => 'int'
    ];

    protected $fillable = [
        'id',
        'ip_address',
        'save_timestamp',
        'data'
    ];
}
