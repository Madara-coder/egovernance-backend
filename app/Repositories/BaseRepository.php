<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;

class BaseRepository
{
    public array $rules = [];
    public array $messages = [];
    public object $model;

    public function __construct(object $model)
    {
        $this->model = $model;
    }

    /**
     * Validate data
     *
     * @param object $request
     * @param array $rules
     * @param array $messages
     * @return mixed
     */
    public function validateData(
        object $request,
        array $rules = [],
        array $messages = []
    ): mixed {
        $data = $request->validate(
            array_merge($this->rules, $rules),
            array_merge($this->messages, $messages)
        );

        return $data;
    }

    /**
     * Fetch all
     *
     * @return object
     */
    public function fetchAll(): object
    {
        $model = $this->model->all();
        return $model;
    }

    /**
     * Fetch by id
     *
     * @param integer $id
     * @param array $with
     * @return object
     */
    public function fetch(
        int $id,
        array $with = []
    ): object {
        $model = $this->model::with($with)
            ->whereId($id)
            ->firstOrFail();
        return $model;
    }

    /**
     * Create
     *
     * @param array $data
     * @param callable|null $callback
     * @return object
     */
    public function create(
        array $data,
        ?callable $callback = null
    ): object {
        DB::beginTransaction();

        try {
            $rows = $this->model::create($data);
            if ($callback) {
                $callback($rows);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
        return $rows;
    }

    /**
     * update
     *
     * @param integer $id
     * @param array $data
     * @param callable|null $callback
     * @return object
     */
    public function update(
        int $id,
        array $data,
        ?callable $callback = null
    ): object {
        DB::beginTransaction();

        try {
            $rows = $this->model::findOrFail($id);
            $rows = $rows->update($data);
            if ($callback) {
                $callback($rows);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
        return $rows;
    }

    /**
     * Destroy
     *
     * @param integer $id
     * @param callable|null $callback
     * @return object
     */
    public function destroy(
        int $id,
        ?callable $callback = null
    ): object {
        try {
            $rows = $this->fetch($id);
            $rows->delete();
            if ($callback) {
                $callback($rows);
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return $rows;
    }
}
