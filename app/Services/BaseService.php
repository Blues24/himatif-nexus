<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * BaseService — provides shared CRUD operations that concrete services can
 * extend. Enforces SRP by keeping controllers thin while giving services a
 * reusable foundation.
 */
abstract class BaseService
{
    /** @var string The Eloquent model class */
    abstract protected string $model { get; }

    /** @var array Columns safe for mass assignment / listing */
    protected array $fillableColumns = [];

    /** @var array Default eager-load relationships */
    protected array $withRelations = [];

    // ------------------------------------------------------------------
    // C — CREATE
    // ------------------------------------------------------------------

    /**
     * Create a new record with validation-ready fillable columns.
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
     * Hook for subclasses to run post-create logic (e.g. audit logs).
     */
    protected function afterCreate(object $record, array $data): void {}

    // ------------------------------------------------------------------
    // R — READ
    // ------------------------------------------------------------------

    /**
     * Find by primary key or fail.
     */
    public function findOrFail(int|string $id): object
    {
        $builder = app($this->model)->with($this->withRelations);

        // Detect custom primary key (e.g. uuid, snake_case)
        $primaryKey = app($this->model)->getKeyName();

        return $builder->where($primaryKey, $id)->firstOrFail();
    }

    /**
     * List all records with optional scope filter and pagination.
     *
     * @param  callable|null  $scope  Accepts an Eloquent\Builder for filtering
     * @param  int  $perPage  Pages per request
     */
    public function paginate(?callable $scope = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = app($this->model)->with($this->withRelations)->latest();

        if ($scope !== null) {
            $query = $scope($query);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get an unpaginated collection (for dropdowns, exports).
     */
    public function all(?callable $scope = null): Collection
    {
        $query = app($this->model)->with($this->withRelations);

        if ($scope !== null) {
            $query = $scope($query);
        }

        return $query->get();
    }

    // ------------------------------------------------------------------
    // U — UPDATE
    // ------------------------------------------------------------------

    /**
     * Update a record identified by its primary key.
     */
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
     * Hook for post-update logic.
     */
    protected function afterUpdate(object $record, array $data): void {}

    // ------------------------------------------------------------------
    // D — DELETE
    // ------------------------------------------------------------------

    /**
     * Soft-delete (if available) or permanently delete.
     */
    public function delete(int|string $id): bool
    {
        $primaryKey = app($this->model)->getKeyName();

        $record = app($this->model)->where($primaryKey, $id)->firstOrFail();

        if (method_exists($record, 'delete')) {
            return $record->delete();
        }

        return $record->forceDelete();
    }
}
