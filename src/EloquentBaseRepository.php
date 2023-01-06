<?php

namespace EloquentRepo;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Base implementation of repository interface to accommodate Eloquent models.
 *
 * @package App\Interfaces
 * @author Imtiaz Rahi
 * @since 2022-11-06
 * @see https://dev.to/carlomigueldy/getting-started-with-repository-pattern-in-laravel-using-inheritance-and-dependency-injection-2ohe
 */
class EloquentBaseRepository implements EloquentRepositoryInterface
{
    /** Name of the database model */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function findById($recordId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->findOrFail($recordId)->append($appends);
    }

    public function create(array $payload): ?Model
    {
        if ($this->fixPayload($payload) == null) return false;
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function update($recordId, array $payload): bool
    {
        if ($this->fixPayload($payload) == null) return false;
        $model = $this->findById($recordId);
        return $model->update($payload);
    }

    /**
     * Eloquent expects payload for create or update as array data.
     *
     * @param $data
     * @return array
     */
    private function fixPayload($data): array {
        switch (gettype($data)) {
            case "array": return $data;
            break;
            case "object": return $data->attributesToArray();
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
        return $this->findById($recordId)->delete();
    }

    public function restoreById($recordId): bool
    {
        return $this->findTrashedById($recordId)->restore();
    }

    public function permanentlyDeleteById($recordId): bool
    {
        return $this->findTrashedById($recordId)->forceDelete();
    }

    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function findTrashedById($recordId): ?Model
    {
        return $this->model->withTrashed()->findOrFail($recordId);
    }

    public function save(array $payload): ?Model
    {
        return $this->create($payload);
    }

    public function find($recordId, array $criteria = [], array $columns = ['*'], array $relations = [], array $appends = []): ?Collection
    {
        return $this->model->with($relations)->where($criteria)->get($columns);
    }

}
