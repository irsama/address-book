<?php

namespace App\Factories;

use App\Entity\City;
use Faker\Factory;

class CityFactory
{
    protected static $faker;
    public static function create(): City
    {
        self::$faker = Factory::create();
        $city = new City();
        $city->setTitle(self::$faker->city);
        return $city;
    }
}