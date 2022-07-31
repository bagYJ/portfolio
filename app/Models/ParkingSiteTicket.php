<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ParkingSiteTicket
 *
 * @property int         $no_product
 * @property int         $no_parking_site
 * @property string      $id_site
 * @property string      $nm_product
 * @property int         $cd_ticket_type
 * @property int         $cd_ticket_day_type
 * @property string      $ds_parking_start_time
 * @property string      $ds_parking_end_time
 * @property string      $ds_selling_days
 * @property string      $ds_selling_start_time
 * @property string      $ds_selling_end_time
 * @property float       $at_price
 * @property string      $cd_selling_status
 *
 * @property ParkingSite $parking_site
 *
 * @package App\Models
 */
class ParkingSiteTicket extends Model
{
    use Compoships;

    protected $table = 'parking_site_ticket';
    protected $primaryKey = 'no_product';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts
        = [
            'no_product'         => 'int',
            'no_parking_site'    => 'int',
            'cd_ticket_type'     => 'int',
            'cd_ticket_day_type' => 'int',
            'at_price'           => 'float'
        ];

    protected $fillable
        = [
            'id_site',
            'no_parking_site',
            'nm_product',
            'cd_ticket_type',
            'cd_ticket_day_type',
            'ds_parking_start_time',
            'ds_parking_end_time',
            'ds_selling_days',
            'ds_selling_start_time',
            'ds_selling_end_time',
            'at_price',
            'cd_selling_status'
        ];

    public function parkingSite(): BelongsTo
    {
        return $this->belongsTo(
            ParkingSite::class,
            'no_parking_site',
            'no_parking_site'
        );
    }
}
