<?php

namespace App\Tests\Unit;

use App\Factories\CityFactory;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{
    public function testCityHasTitle(): void
    {
        $city = CityFactory::create();
        $this->assertIsString($city->getTitle());
    }
}