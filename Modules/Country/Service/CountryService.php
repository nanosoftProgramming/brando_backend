<?php

namespace Modules\Country\Service;

use Modules\Country\App\Models\City;
use Modules\Country\App\Models\Country;

class CountryService
{
    public function findAllCountries($data = [], $relations = [])
    {
        $countries = Country::query()->select('id', 'name')->with($relations)->latest();

        return getCaseCollection($countries, $data);
    }

    public function findCitiesByCountryId($countryId, $data = [], $relations = [])
    {
        $cities = City::query()->select('id', 'name')->where('country_id', $countryId)->with($relations)->latest();

        return getCaseCollection($cities, $data);
    }
}
