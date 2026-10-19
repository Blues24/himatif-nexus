<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * BaseService — shared CRUD operations for concrete services. Keeps controllers thin.
 */
abstract class BaseService
{
    /** @var string The Eloquent model class. */
    abstract protected string $model { get; }

    /** @var array Columns safe for mass assignment / listing. */
    protected array $fillableColumns = [];

    /** @var array Default eager-load relationships. */
    protected array $withRelations = [];

    /**
     * Create wrapped in a transaction. Only fills from allowed columns.
     */
    public function create(array $data): object
    {
        return DB::transaction(function () use ($data) {
            $record = app($this->model)->create(array_only($data, $this->fillableColumns));

            $this->afterCreate($record, $data);

            return $record;
        });
    }

    /**
     * Subclass hook for post-create side effects (notifications, audit logs, etc.).
     */
    protected function afterCreate(object $record, array $data): void {}

    /**
     * Resolve a record using the model's actual primary key (supports uuid, snake_case, etc.).
     */
    public function findOrFail(int|string $id): object
    {
        $primaryKey = app($this->model)->getKeyName();

        return app($this->model)->with($this->withRelations)->where($primaryKey, $id)->firstOrFail();
    }

    public function paginate(?callable $scope = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = app($this->model)->with($this->withRelations)->latest();

        if ($scope !== null) {
            $query = $scope($query);
        }

        return $query->paginate($perPage);
    }

    /**
     * Unpaginated collection — for dropdowns, exports, etc.
     */
    public function all(?callable $scope = null): Collection
    {
        $query = app($this->model)->with($this->withRelations);

        if ($scope !== null) {
            $query = $scope($query);
        }

        return $query->get();
    }

    public function update(int|string $id, array $data): bool
    {
        $primaryKey = app($this->model)->getKeyName();

        $record = app($this->model)->where($primaryKey, $id)->firstOrFail();

        return DB::transaction(function () use ($record, $data) {
            $record->update(array_only($data, $this->fillableColumns));

            $this->afterUpdate($record, $data);

            return true;
        });
    }

    /**
     * Subclass hook for post-update side effects.
     */
    protected function afterUpdate(object $record, array $data): void {}

    /**
     * Delete via soft-delete if the trait is present, otherwise hard delete.
     */
    public function delete(int|string $id): bool
    {
        $primaryKey = app($this->model)->getKeyName();

        $record = app($this->model)->where($primaryKey, $id)->firstOrFail();

        return method_exists($record, 'delete') ? $record->delete() : $record->forceDelete();
    }

    // ------------------------------------------------------------------
    // MISC HELPERS
    // ------------------------------------------------------------------

    /**
     * Count matching records with an optional scope.
     */
    public function count(?callable $scope = null): int
    {
        $query = app($this->model);

        if ($scope !== null) {
            $query = $scope($query);
        }

        return $query->count();
    }
}
