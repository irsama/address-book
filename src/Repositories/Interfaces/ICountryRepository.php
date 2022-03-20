<?php

namespace App\Repositories\Interfaces;

use App\Entity\Country;
use Doctrine\Common\Collections\Collection;

/**
 * Interface DoctrineRepositoryInterface
 * @package App\Repositories
 */
interface ICountryRepository extends IBaseRepository
{
    /**
     * @param $id
     * @return Country|null
     */
    public function find($id): ?Country;

    /**
     * @param Country $country
     * @return Country
     */
    public function create(Country $country): Country;

    /**
     * @param $id
     * @return Collection|null
     */
    public function getCities($id): ?Collection;
}
