<?php

namespace App\Repositories\Doctrine;

use App\Entity\Address;
use App\Repositories\Interfaces\IAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class AddressRepository implements IAddressRepository
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
        $this->objectRepository = $this->entityManager->getRepository(Address::class);
    }
    public function create(Address $address): Address
    {
        $this->entityManager->persist($address);
        $this->entityManager->flush();
        return ($address);
    }

    public function update($id, array $attributes): bool
    {
        $address = $this->objectRepository->find($id);
        $this->entityManager->persist($address);
        $this->entityManager->flush();
    }

    public function delete(array $id): bool
    {
        $address = $this->objectRepository->find($id);
        $this->entityManager->remove($address);
        $this->entityManager->flush();
    }

    public function find($id): ?Address
    {
        return $this->objectRepository->find($id);
    }

    public function getAll($first= -1, $rows = 7): ?array
    {
        return $first === -1 ? $this->objectRepository->findAll()
            :$this->objectRepository->findBy([],[],$rows,$first);
    }
}