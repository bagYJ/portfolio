<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class ConfigPushmsg
 *
 * @property int $no
 * @property string|null $cd_push
 * @property string|null $ds_push
 *
 * @package App\Models
 */
class ConfigPushmsg extends Model
{
    protected $primaryKey = 'no';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int'
    ];

    protected $fillable = [
        'cd_push',
        'ds_push'
    ];
}
