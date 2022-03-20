<?php

namespace App\Repositories\Doctrine;

use App\Entity\City;
use App\Repositories\Interfaces\ICityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class CityRepository implements ICityRepository
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
        $this->objectRepository = $this->entityManager->getRepository(City::class);
    }
    public function find($id): ?City
    {
        return $this->objectRepository->find($id);
    }
    public function getAll(): ?array
    {
        return $this->objectRepository->findAll();
        /*return $this->entityManager->createQuery(
            'select c from App\Entity\City c order by c.title'
        )->getArrayResult();*/
    }
}