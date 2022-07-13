<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\Interfaces\GeographyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GeographyRepository extends BaseRepository implements GeographyRepositoryInterface
{
    public function getAllCountries(): Collection
    {
        return Country::all();
    }
}

