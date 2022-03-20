<?php

namespace App\Services;

use App\Entity\Country;
use App\Repositories\Doctrine\CountryRepository;

class CountryService
{
    private $countryRepository;
    public function __construct(CountryRepository $countryRepository){
        $this->countryRepository = $countryRepository;
    }
    public function find($id): Country
    {
        return $this->countryRepository->find($id);
    }
    public function getAll(): array
    {
        return $this->countryRepository->getAll();
    }
    public function getCities($id): ?array
    {
        $cityList = $this->countryRepository->getCities($id);
        $cities = [];
        foreach ($cityList as $city){
            array_push($cities, ['id'=>$city->getId(),'title'=>$city->getTitle()]);
        }
        return $cities;
    }
    public function create(Country $country): Country
    {
        return $this->countryRepository->create($country);
    }
}