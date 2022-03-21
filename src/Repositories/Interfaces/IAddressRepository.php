<?php

namespace App\Repositories\Interfaces;

use App\Entity\Address;

/**
 * Interface DoctrineRepositoryInterface
 * @package App\Repositories
 */
interface IAddressRepository extends IBaseRepository
{
    /**
     * @param Address $address
     * @return Address
     */
    public function create(Address $address): Address;

    /**
     * @param int $id
     * @param Address $address
     * @return Address
     */
    public function update(int $id, Address $address): Address;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): void;

    /**
     * @param $id
     * @return Address
     */
    public function find($id): ?Address;
}
