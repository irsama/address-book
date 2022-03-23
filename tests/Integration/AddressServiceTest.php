<?php

namespace App\Tests\Integration;

use App\Factories\AddressFactory;
use App\Tests\PrepareTestDatabase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddressServiceTest extends KernelTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        PrepareTestDatabase::load();
    }
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
    public function testUpdateAddressFromDatabase()
    {
        self::bootKernel();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $address = AddressFactory::create();
        $currentAddress = $addressService->create($address);

        $addressForUpdate = AddressFactory::create();
        $currentAddress->setFirstName($addressForUpdate->getFirstName());
        $updatedAddress = $addressService->update($address->getId() ,$currentAddress);

        $this->assertEquals($updatedAddress->getFirstName(),$addressForUpdate->getFirstName());
    }
    public function testDeleteAddressFromDatabase()
    {
        self::bootKernel();
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        $address = AddressFactory::create();
        $address = $addressService->create($address);
        $addressId = $address->getId();
        $addressList = $addressService->getAll();
        $addressIds = [];
        foreach ($addressList as $addressIndividual){
            array_push($addressIds,$addressIndividual->getId());
        }
        $this->assertContains($addressId,$addressIds);
        $addressService->delete($address->getId());
        $addressList = $addressService->getAll();
        $addressIds = [];
        foreach ($addressList as $addressIndividual){
            array_push($addressIds,$addressIndividual->getId());
        }
        $this->assertNotContains($addressId,$addressIds);
    }
}
