<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ParkingSiteImage
 *
 * @property int $no_parking_site
 * @property string $id_site
 * @property string $ds_image_url
 *
 * @property ParkingSite $parking_site
 *
 * @package App\Models
 */
class ParkingSiteImage extends Model
{
    protected $table = 'parking_site_image';

    protected $primaryKey = 'no_rsv';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no_parking_site' => 'int'
    ];

    protected $fillable = [
        'id_site',
    ];

    public function parkingSite(): BelongsTo
    {
        return $this->belongsTo(ParkingSite::class, 'no_parking_site', 'no_parking_site');
    }
}
