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
     * @param $id
     * @param array $attributes
     * @return bool
     */
    public function update($id, array $attributes): bool;

    /**
     * @param array $id
     * @return bool
     */
    public function delete(array $id): bool;

    /**
     * @param $id
     * @return Address
     */
    public function find($id): ?Address;
}
