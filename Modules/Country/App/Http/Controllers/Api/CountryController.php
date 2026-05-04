<?php

namespace Modules\Country\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Country\App\resources\CityResource;
use Modules\Country\App\resources\CountryResource;
use Modules\Country\Service\CountryService;

class CountryController extends Controller
{
    private $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function countries(Request $request)
    {
        $data = $request->all();
        $relations = [];
        $countries = $this->countryService->findAllCountries($data, $relations);

        return returnMessage(true, 'Countries Fetched Successfully', CountryResource::collection($countries)->response()->getData(true));
    }

    public function cities(Request $request, $countryId)
    {
        $data = $request->all();
        $relations = [];
        $cities = $this->countryService->findCitiesByCountryId($countryId, $data, $relations);

        return returnMessage(true, 'Cities Fetched Successfully', CityResource::collection($cities)->response()->getData(true));
    }
}
