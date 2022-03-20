<?php

namespace App\Tests\Integration;

use App\Factories\AddressFactory;
use App\Factories\CountryFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddressServiceTest extends KernelTestCase
{
    public function testReturnAddressList()
    {
        self::bootKernel();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $addressList = $addressService->getAll();
        $this->assertIsArray($addressList);
    }
    public function testReturnAddressListWithLimitation()
    {
        self::bootKernel();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        for ($counter=0;$counter<10;$counter++){
            $address = AddressFactory::create();
            $addressService->create($address);
        }
        $first=0;
        $rows=7;
        $addressList = $addressService->getAll($first, $rows);
        $this->assertIsArray($addressList);
        $this->assertCount($rows, $addressList);
    }
    public function testAddNewAddressToDatabase()
    {
        self::bootKernel();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $address = AddressFactory::create();
        $address = $addressService->create($address);
        $this->assertIsInt($address->getId());
    }
}
