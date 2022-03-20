<?php

namespace App\Services;

use App\Entity\City;
use App\Repositories\Doctrine\CityRepository;

class CityService
{
    private $cityRepository;
    public function __construct(CityRepository $cityRepository){
        $this->cityRepository = $cityRepository;
    }
    public function find($id): City
    {
        return $this->cityRepository->find($id);
    }
    public function getAll(): array
    {
        return $this->cityRepository->getAll();
    }
}