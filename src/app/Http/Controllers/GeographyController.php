<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\GeographyRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GeographyController extends Controller
{
    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly GeographyRepositoryInterface $repository
    ) {
        $this->middleware('auth');
    }

    public function countries()
    {
        /** @var Collection $countries */
        $countries = Cache::rememberForever('countries', function () {
            return $this->repository->getAllCountries();
        });

        return $this->respondData($countries);
    }
}
