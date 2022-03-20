<?php

namespace App\Factories;

use App\Entity\City;
use App\Entity\Country;
use Faker\Factory;

class CountryFactory
{
    protected static $faker;
    public static function create(): Country
    {
        self::$faker = Factory::create();
        $country = new Country();
        $country->setTitle(self::$faker->country);
        for($counter=0; $counter<rand(3,5); $counter++) {
            $city = new City();
            $city->setTitle(self::$faker->city);
            $country->addCity($city);
        }
        return $country;
    }
}