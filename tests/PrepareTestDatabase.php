<?php

namespace App\Tests;

use App\Factories\AddressFactory;
use App\Factories\CountryFactory;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PrepareTestDatabase  extends KernelTestCase
{
    public static function load(){
        $entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();
        //In case leftover entries exist
        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        // Drop and recreate tables for all entities
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $files = glob('uploadedFiles/targetTmp/*'); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file)) {
                unlink($file); // delete file
            }
        }

        self::createCountries();
        self::createAddresses();

    }
    private static function createCountries(){
        $container = static::getContainer();
        $countryService = $container->get('app.country');
        for($counter=1;$counter<7;$counter++){
            $country = CountryFactory::create();
            $countryService->create($country);
        }
    }
    private static function createAddresses(){
        $container = static::getContainer();
        $addressService = $container->get('app.address');
        for ($counter=0;$counter<10;$counter++){
            $address = AddressFactory::create();
            $addressService->create($address);
        }
    }
}