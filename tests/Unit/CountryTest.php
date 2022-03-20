<?php

namespace App\Tests\Unit;

use App\Factories\CountryFactory;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    public function testCountryHasTitle(): void
    {
        $country = CountryFactory::create();
        $this->assertIsString($country->getTitle());
    }
    public function testCountryHasCities(): void
    {
        $country = CountryFactory::create();
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $country->getCities());
    }
}