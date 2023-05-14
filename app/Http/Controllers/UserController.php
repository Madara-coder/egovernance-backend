<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    use ResponseAPI;

    protected $userResource;
    protected string $userName;

    public function __construct(
        protected UserRepository $userRepository
    ) {
        $this->userName = Auth::user()->name;
    }

    /**
     * Creates instance of user resource
     *
     * @param object $data
     * @return object
     */
    public function resource(object $data): object
    {
        return new UserResource($data);
    }

    /**
     * Creates new anonymous resource collection
     *
     * @param object $data
     * @return object
     */
    public function collection(object $data): object
    {
        return UserResource::collection($data);
    }

    /**
     * Fetch all
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $users = $this->userRepository->fetchAll();
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponse(
            message: __("custom.response.success"),
            data: $this->collection($users)
        );
    }

    /**
     * Fetch by id
     *
     * @param integer $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->fetch($id);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponse(
            message: __(
                "custom.response.show",
                ['name' => $this->userName]
            ),
            data: $this->resource($user)
        );
    }

    /**
     * Update
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function update(
        Request $request,
        int $id
    ): JsonResponse {
        try {
            $user = $this->userRepository->update($id, $request->all());
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponse(
            message: __(
                "custom.response.update",
                ['name' => $this->userName]
            ),
            data: $this->resourse($user)
        );
    }

    /**
     * Destroy
     *
     * @param integer $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->destroy($id);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponse(
            message: __(
                "custom.response.delete",
                ['name' => $this->userName]
            ),
            data: $this->resource($user)
        );
    }
}
