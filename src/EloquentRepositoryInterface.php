<?php

namespace EloquentRepo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Repository interface to accommodate Eloquent models.
 * TODO need method with orderBy option
 *
 * @package App\Interfaces
 * @author Imtiaz Rahi
 * @since 2022-11-06
 * @see https://dev.to/carlomigueldy/getting-started-with-repository-pattern-in-laravel-using-inheritance-and-dependency-injection-2ohe
 */
interface EloquentRepositoryInterface
{

    /**
     * Get all models.
     *
     * @param array $columns Columns of a DB table
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Find model by record id.
     *
     * @param mixed $recordId Record id
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model|null
     */
    public function findById($recordId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model;

    /**
     * Create a model with payload.
     *
     * @param array $payload
     * @return Model|null
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing model.
     *
     * @param mixed $recordId
     * @param array $payload
     * @return bool
     */
    public function update($recordId, array $payload): bool;

    /**
     * Archive selected record (0row) by it's id.
     *
     * @param mixed $recordId
     * @return bool
     */
    public function archive($recordId): bool;

    /**
     * Delete model by id.
     *
     * @param mixed $recordId
     * @return bool
     */
    public function deleteById($recordId): bool;

    public function restoreById($recordId): bool;

    public function permanentlyDeleteById($recordId): bool;

    /**
     * Get all trashed collections.
     *
     * @return Collection
     */
    public function allTrashed(): Collection;

    /**
     * Find trashed model by id.
     *
     * @param mixed $recordId
     * @return Model|null
     */
    public function findTrashedById($recordId): ?Model;

    /**
     * @param array $payload
     * @return Model|null
     */
    public function save(array $payload): ?Model;

    /**
     * Find records with a single where clause.
     *
     * @param mixed $recordId Record id
     * @param array $criteria Eloquent Where clause
     * @param array $columns Column names (optional)
     * @param array $relations Table relations (optional)
     * @param array $appends
     * @return Collection|null
     */
    public function find($recordId, array $criteria = [], array $columns = ['*'], array $relations = [], array $appends = []): ?Collection;

}
