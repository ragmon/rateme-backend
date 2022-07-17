<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int owner_id
 * @property string owner_type
 * @property Model owner
 * @property string path
 * @property boolean is_main
 * @property string driver
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Photo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = [
        'driver',
        'owner_type',
        'owner_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
