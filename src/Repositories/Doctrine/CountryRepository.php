<?php

namespace App\Repositories\Doctrine;

use App\Entity\Country;
use App\Repositories\Interfaces\ICountryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class CountryRepository implements ICountryRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ObjectRepository
     */
    private $objectRepository;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Country::class);
    }
    public function find($id): ?Country
    {
        return $this->objectRepository->find($id);
    }
    public function create(Country $country): Country
    {
        $this->entityManager->persist($country);
        $this->entityManager->flush();
        return ($country);
    }
    public function getCities($id): ?Collection
    {
        $country = $this->objectRepository->find($id);
        return $country->getCities();
    }
    public function getAll(): ?array
    {
        return $this->objectRepository->findAll();
    }
}