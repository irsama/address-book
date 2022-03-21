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

    public function update(int $id, Address $address): Address
    {
        $currentAddress = $this->objectRepository->find($id);
        $currentAddress->setFirstName($address->getFirstName());
        $currentAddress->setLastName($address->getLastName());
        $currentAddress->setStreetAndNumber($address->getStreetAndNumber());
        $currentAddress->setZip($address->getZip());
        $currentAddress->setBirthday($address->getBirthday());
        $currentAddress->setPhoneNumber($address->getPhoneNumber());
        $currentAddress->setEmailAddress($address->getEmailAddress());
        $currentAddress->setPicture($address->getPicture());
        $this->entityManager->persist($currentAddress);
        $this->entityManager->flush();
        return ($currentAddress);
    }

    public function delete(int $id): void
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