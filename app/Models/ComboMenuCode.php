<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class ComboMenuCode
 *
 * @property string $classa
 * @property string|null $class1
 * @property string|null $class2
 * @property string|null $class3
 * @property string|null $class4
 * @property string|null $class5
 * @property string|null $class6
 * @property string|null $name1
 * @property string|null $name2
 * @property int|null $sortkey
 *
 * @package App\Models
 */
class ComboMenuCode extends Model
{
    protected $primaryKey = 'classa';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'sortkey' => 'int'
    ];

    protected $fillable = [
        'class1',
        'class2',
        'class3',
        'class4',
        'class5',
        'class6',
        'name1',
        'name2',
        'sortkey'
    ];
}
