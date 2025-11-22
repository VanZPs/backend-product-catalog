<?php

namespace App\Http\Controllers;

use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class LocationController extends Controller
{
    public function provinces()
    {
        return Province::select('code', 'name')->get();
    }

    public function cities($province)
    {
        return City::where('province_code', $province)
            ->select('code', 'name')
            ->get();
    }

    public function districts($city)
    {
        return District::where('city_code', $city)
            ->select('code', 'name')
            ->get();
    }

    public function villages($district)
    {
        return Village::where('district_code', $district)
            ->select('code', 'name')
            ->get();
    }
}
