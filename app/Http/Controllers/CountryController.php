<?php

namespace App\Http\Controllers;

use App\Models\ApiCountry;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CountryController extends ApiController
{
    public function seedCountries()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Country::truncate();
        $baseUrl = 'https://restcountries.eu/rest/v2/';
        $response = Http::get($baseUrl . 'all');
//        dd($response->json());
        if ($response->ok()) {
            $apiCountries = $response->json();

            foreach ($apiCountries as $apiCountry) {
                (new Country)->create([
                    'name' => $apiCountry['name'] ?? '-',
                    'is_covered' => strtolower($apiCountry['name']) === 'nigeria' ? Country::IS_COVERED_TRUE : Country::IS_COVERED_FALSE,
                    'calling_code' => $apiCountry['callingCodes'][0] ?? '000',
                    'alpha_2_code' => $apiCountry['alpha2Code'] ?? 000,
                    'alpha_3_code' => $apiCountry['alpha3Code'] ?? 000,
                ]);
            }
            $countries = Country::all('name');
            return $this->showAll($countries);

        }

        return $this->errorResponse('Country seeding failed.');
    }

    public function activateCountry(Country $country)
    {
        $country->is_covered = Country::IS_COVERED_TRUE;
        $country->save();

        return $this->showOne($country);
    }

    public function getActiveCountries()
    {
        $coutries = Country::where('is_covered', Country::IS_COVERED_TRUE)->get();
        return $this->showAll($coutries);
    }

    public function seedStates()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        State::truncate();
        $url = 'http://www.westclicks.com/webservices/?f=json&c=';
        $countries = Country::all(['id', 'alpha_2_code'])->toArray();

        foreach ($countries as $country) {
            $code = $country['alpha_2_code'];
            $response = Http::get($url . $code);
            if ($response->ok()) {
                $states = $response->json();
                if ($states && count($states) > 0) {
                    foreach ($states as $state) {
                        State::create([
                            'country_id' => $country['id'],
                            'name' => $state
                        ]);
                    }
                }
            }
        }
        $states = State::all('name');
        return $this->showAll($states);
    }

    public function getCountryStates(Country $country)
    {
        $states = $country->states;
        return $this->showAll($states);
    }

    public function getCountryFlag($country_name)
    {
        $baseUrl = 'https://restcountries.eu/rest/v2/name/';
        $response = Http::get($baseUrl . $country_name);
        if ($response->ok()) {
            $country_data = $response->json();
            $flag = $country_data[0]['flag'];
            if ($flag) {
                return $this->showOne($flag);
            }
            return $this->errorResponse('Error fetching country flag');
        }
        return $this->errorResponse('Error fetching country flag');

    }
}
