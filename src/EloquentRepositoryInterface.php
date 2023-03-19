<?php

namespace EloquentRepo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Repository interface to accommodate Eloquent entity models.
 * TODO need method with orderBy option
 * TODO sorting feature
 * TODO saveAll(array of entities), findAllById(array of ids)
 * TODO existsById(id);
 * TODO count
 *
 * @package EloquentRepo
 * @author Imtiaz Rahi
 * @since 2022-11-06
 * @see https://dev.to/carlomigueldy/getting-started-with-repository-pattern-in-laravel-using-inheritance-and-dependency-injection-2ohe
 */
interface EloquentRepositoryInterface
{

    /**
     * Get all rows from the database table as Collection of Model.
     *
     * @param array $columns Columns of a DB table
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Check whether a record exists (by id column) in the table.
     *
     * @param $recordId Record id
     * @return bool True if record exists, False otherwise
     */
    public function existsById($recordId): bool;

    /**
     * Find a row by record id and get the object as Model instance.
     *
     * @param mixed $recordId Record id
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model|null
     */
    public function findById($recordId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model;

    /** Get a record by short, unique <i><b>code</b></i> column */
    public function findByCode($code, array $columns = ['*']): ?Model;

    /**
     * Create or save an entity model in database with the provided data payload.
     * Returns the inserted Model with record id.
     *
     * @param array $payload
     * @return Model|null
     */
    public function create(array $payload): ?Model;

    /**
     * Create or save an entity model in database with the provided data payload.
     * Returns the inserted Model with record id.
     * Provides same functionality as the create method.
     *
     * @param array $payload
     * @return Model|null
     */
    public function save(array $payload): ?Model;

    /**
     * Update existing entity model from provided data payload.
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
     * Find records with a single where clause.
     *
     * @param array $criteria Eloquent Where clause
     * @param array $columns Column names (optional)
     * @param array $relations Table relations (optional)
     * @param array $appends
     * @return Collection|null
     */
    public function find(array $criteria = [], array $columns = ['*'], array $relations = [], array $appends = []): ?Collection;

    public function findLimited(array $criteria = [], int $limit, array $columns = ['*'], array $relations = [], array $appends = []): ?Collection;

    /**
     * Find records with where clause with order by feature and default limit set to 10.
     * <pre>
     * findOrderedLimited(['method' => 'bank', 'is_checked' => true], ['full_name'], 15, ['full_name', 'email', 'mobile']);
     * findOrderedLimited(['count', '=>', 10], ['*'], ['full_name', 'desc'], 15);
     * 'created_at', '<', now()->subDays(7)
     * </pre>
     *
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit Default limit is 10
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Collection|null
     */
    public function findOrderedLimited(array $criteria = [], array $orderBy = [], int $limit, array $columns = ['*'], array $relations = [], array $appends = []): ?Collection;
}
