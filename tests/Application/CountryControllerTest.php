<?php

namespace App\Tests\Application;

use App\Factories\CityFactory;
use App\Factories\CountryFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CountryControllerTest extends WebTestCase
{
    public function testCountryGetCitiesReturnCities(): void
    {

        $client = static::createClient();

        $container = static::getContainer();
        $countryService = $container->get('app.country');
        for($counter=1;$counter<5;$counter++){
            $country = CountryFactory::create();
            $countryService->create($country);
        }

        $crawler = $client->xmlHttpRequest('GET', '/country/cities', ['id'=>'1']);

        $this->assertResponseIsSuccessful();

        $JSON_response = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($JSON_response);
    }
}
