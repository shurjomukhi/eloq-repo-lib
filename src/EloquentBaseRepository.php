<?php

namespace EloquentRepo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

/**
 * Base implementation of repository interface to accommodate Eloquent entity models.
 *
 * @package EloquentRepo
 * @author Imtiaz Rahi
 * @since 2022-11-06
 * @see https://dev.to/carlomigueldy/getting-started-with-repository-pattern-in-laravel-using-inheritance-and-dependency-injection-2ohe
 * @see https://github.com/carlomigueldy/laravel-repository-pattern
 */
class EloquentBaseRepository implements EloquentRepositoryInterface
{
    /** Eloquent entity model type class representing the database table */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getTableName(): string
    {
        return $this->model->getTable();
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    // select count('id') as aggregate from table_name where id = ?
    public function existsById($recordId): bool {
        $fld = $this->model->getKeyName();
        return $this->model->where($fld, $recordId)->count($fld) > 0;
    }

    public function findById($recordId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model
    {
        try {
            return $this->model->select($columns)->with($relations)->findOrFail($recordId)->append($appends);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function findByCode($code, array $columns = ['*']): ?Model
    {
        return $this->model->select($columns)->where('code', $code)->first();
    }

    public function create(array $payload): ?Model
    {
        if ($this->fixPayload($payload) == null) return false;
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function save(array $payload): ?Model
    {
        return $this->create($payload);
    }

    public function update($recordId, array $payload): bool
    {
        if ($this->fixPayload($payload) == null) return false;
        $model = $this->findById($recordId);
        return $model != null && $model->update($payload);
    }

    /**
     * Eloquent expects payload for create or update as array data.
     *
     * @param $data
     * @return array
     */
    private function fixPayload($data): array
    {
        switch (gettype($data)) {
            case "array":
                return $data;
                break;
            case "object":
                return $data->attributesToArray();
                break;
        }
        return false;
    }

    /**
     * Archive (soft delete) individual record.
     *
     * @param int $recordId
     * @return bool
     */
    public function archive($recordId): bool
    {
        $model = $this->findById($recordId);
        //TODO update that record by setting archive field to true;
        return true;
    }

    public function deleteById($recordId): bool
    {
        $model = $this->findById($recordId);
        return $model != null && $model->delete();
    }

    public function restoreById($recordId): bool
    {
        $model = $this->findTrashedById($recordId);
        return $model != null && $model->restore();
    }

    public function permanentlyDeleteById($recordId): bool
    {
        $model = $this->findTrashedById($recordId);
        return $model != null && $model->forceDelete();
    }

    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function findTrashedById($recordId): ?Model
    {
        try {
            return $this->model->withTrashed()->findOrFail($recordId);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function find(array $criteria = [], array $columns = ['*'], array $relations = [], array $appends = []): ?Collection
    {
        return $this->model->with($relations)->where($criteria)->get($columns)->append($appends);
    }

    public function findLimited(array $criteria = [], int $limit = 10, array $columns = ['*'], array $relations = [], array $appends = []): ?Collection
    {
        return $this->model->with($relations)->where($criteria)->limit($limit)->get($columns)->append($appends);
    }

    public function findOrderedLimited(array $criteria = [], array $orderBy = [""], int $limit = 10, array $columns = ['*'], array $relations = [], array $appends = []): ?Collection
    {
        if (empty($orderBy) || empty($orderBy[0])) throw new InvalidArgumentException('Column to order must be present');
        return $this->model->with($relations)->where($criteria)->orderBy($orderBy[0], $orderBy[1])->limit($limit)->get($columns)->append($appends);
    }

}
