<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $countries = [
            [
              'title' => 'Germany',
              'cities' => [
                  ['title' => 'Berlin'],
                  ['title' => 'Hamburg'],
                  ['title' => 'Munich'],
                  ['title' => 'Stuttgart'],
                  ['title' => 'Dortmund'],
                  ['title' => 'Bremen'],
              ]
            ],
            [
                'title' => 'USA',
                'cities' => [
                    ['title' => 'New York'],
                    ['title' => 'Los Angeles'],
                    ['title' => 'Houston'],
                    ['title' => 'San Antonio'],
                ]
            ],
            [
                'title' => 'Iran',
                'cities' => [
                    ['title' => 'Tehran'],
                    ['title' => 'Qazvin'],
                    ['title' => 'Shiraz'],
                    ['title' => 'Tabriz'],
                    ['title' => 'Mashhad'],
                ]
            ],
            [
                'title' => 'England',
                'cities' => [
                    ['title' => 'London'],
                    ['title' => 'Manchester'],
                    ['title' => 'Birmingham'],
                    ['title' => 'Bristol'],
                    ['title' => 'Cambridge'],
                ]
            ],
            [
                'title' => 'France',
                'cities' => [
                    ['title' => 'Paris'],
                    ['title' => 'Marseille'],
                    ['title' => 'Lyon'],
                    ['title' => 'Nice'],
                ]
            ],
        ];

        foreach ($countries as $countryData) {
            $country = new Country();
            $country->setTitle($countryData['title']);
            $manager->persist($country);
            foreach ($countryData['cities'] as $cityData){
                $city = new City();
                $city->setTitle($cityData['title']);
                $city->setCountry($country);
                $manager->persist($city);
            }
        }

        $manager->flush();
    }
}
