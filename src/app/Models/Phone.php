<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property string name
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Model owner
 * @property int owner_id
 * @property string owner_type
 * @property string phone
 * @property bool is_main
 */
class Phone extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'phone',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }
}
