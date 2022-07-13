<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property string sortname
 * @property string name_en
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Country extends Model
{
    use HasFactory;
}
