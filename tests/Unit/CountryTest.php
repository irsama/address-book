<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    public function testHasACity(): array
    {
        $country = [];
        $this->assertEmpty($country);

        return $country;
    }
}