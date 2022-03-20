<?php

namespace App\Repositories\Interfaces;


/**
 * Interface DoctrineRepositoryInterface
 * @package App\Repositories
 */
interface IBaseRepository
{
    /**
     * @return array
     */
    public function getAll(): ?array;
}
