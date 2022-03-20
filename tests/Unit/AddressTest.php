<?php

namespace App\Tests\Unit;

use App\Entity\City;
use App\Factories\AddressFactory;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testAddressHasACity(): void
    {
        $address = AddressFactory::create();
        $this->assertInstanceOf(City::class , $address->getCity());
    }
    public function testAddressHasAFirstName(): void
    {
        $address = AddressFactory::create();
        $this->assertIsString($address->getFirstName());
    }
    public function testAddressHasALastName(): void
    {
        $address = AddressFactory::create();
        $this->assertIsString($address->getLastName());
    }
    public function testAddressHasABirthDay(): void
    {
        $address = AddressFactory::create();
        $this->assertInstanceOf(\DateTime::class , $address->getBirthday());
    }
    public function testAddressHasAStreetAndNumber(): void
    {
        $address = AddressFactory::create();
        $this->assertIsString($address->getStreetAndNumber());
    }
    public function testAddressHasAPhoneNumber(): void
    {
        $address = AddressFactory::create();
        $this->assertIsString($address->getPhoneNumber());
    }
    public function testAddressHasAEmail(): void
    {
        $address = AddressFactory::create();
        $this->assertIsString($address->getEmailAddress());
    }
    public function testAddressHasAZip(): void
    {
        $address = AddressFactory::create();
        $this->assertIsString($address->getZip());
    }
}