<?php

namespace App\Factories;

use App\Entity\Address;
use App\Entity\City;
use App\Entity\Country;
use Faker\Factory;

class AddressFactory
{
    protected static $faker;
    public static function create($city=null): Address
    {
        self::$faker = Factory::create();
        if($city === null) {
            $country = new Country();
            $country->setTitle(self::$faker->country);

            $city = new City();
            $city->setTitle(self::$faker->city);
            $city->setCountry($country);
        }
        $address = new Address();
        $address->setFirstName(self::$faker->firstName);
        $address->setLastName(self::$faker->lastName);
        $address->setStreetAndNumber('No '.self::$faker->buildingNumber.
            ','.self::$faker->streetName);
        $address->setPhoneNumber(self::$faker->numerify('##########'));
        $address->setZip(self::$faker->postcode);
        $address->setEmailAddress(self::$faker->email);
        $address->setBirthday(new \DateTime(self::$faker->date()));
        $address->setCity($city);
        if(rand(0,100)>70) {
            $address->setPicture(self::$faker->file('uploadedFiles/sourceTmp',
                'uploadedFiles/targetTmp'));
        }
        return $address;
    }
}