<?php

namespace App\Repositories\Interfaces;

use App\Entity\City;

/**
 * Interface DoctrineRepositoryInterface
 * @package App\Repositories
 */
interface ICityRepository extends IBaseRepository
{
    /**
     * @param $id
     * @return City|null
     */
    public function find($id): ?City;
}
