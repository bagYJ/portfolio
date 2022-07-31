<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class ContractDetail
 *
 * @property int $no
 * @property string $ds_question
 * @property string|null $ds_answer
 *
 * @package App\Models
 */
class ContractDetail extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int'
    ];

    protected $fillable = [
        'ds_answer'
    ];
}
