<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class CarMaker
 *
 * @property int $no_maker
 * @property string $nm_maker
 * @property string $yn_korea
 *
 * @package App\Models
 */
class CarMaker extends Model
{
    protected $primaryKey = 'no_maker';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no_maker' => 'int'
    ];

    protected $fillable = [
        'nm_maker',
        'yn_korea'
    ];
}
