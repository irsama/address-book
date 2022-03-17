<?php

namespace App\Repositories\Interfaces;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface IBaseRepository
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

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
     * @return Model
     */
    public function find($id): ?Model;

    /**
     * @return Collection
     */
    public function getAll(): ?Collection;
}
