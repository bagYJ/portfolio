<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class StringTable
 *
 * @property string $ds_string
 * @property string $ds_lang_locale
 * @property string|null $ds_description
 *
 * @package App\Models
 */
class StringTable extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ds_description'
    ];
}
