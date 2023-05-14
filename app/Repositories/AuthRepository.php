<?php

namespace App\Repositories;

use App\Models\User;
use App\Traits\ResponseApi;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthRepository extends BaseRepository
{
    use ResponseAPI;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        parent::__construct($user);
        $this->rules = [
            "name" => "max:255",
            "email" => "required|email",
            "password" => "required|min:6",
            "address" => "max:255",
        ];
    }

    /**
     * User login
     *
     * @param array $data
     * @param callable|null $callback
     * @return array
     */
    public function login(
        array $data,
        ?callable $callback = null
    ): array {
        try {
            $token = Auth::attempt($data);
            $user = Auth::user();

            if ($callback) {
                $callback($user);
            }

            if (!$user) {
                throw new Exception(__("auth.failed"), 404);
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return [$user, $token];
    }

    /**
     * Create user
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
}
