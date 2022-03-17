<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testHasACity(): array
    {
        $city = [];
        $this->assertEmpty($city);

        return $city;
    }
}