<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Traits\ResponseAPI;
use Exception;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    use ResponseAPI;

    public function __construct(
        protected UserRepository $userRepository,
        protected AuthRepository $authRepository
    ) {
        $this->userRepository = $userRepository;
        $this->authRepository = $authRepository;
    }

    /**
     * Login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $data = $this->authRepository->validateData($request);
            $loggedInUser = $this->authRepository->login($data);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponse(
            message: __("auth.login"),
            data: $loggedInUser,
        );
    }

    /**
     * Register/sign up user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $data = $this->userRepository->validateData($request);
            $data["password"] = Hash::make($request->password);
            $registeredUser = $this->userRepository->create($data);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponse(
            message: __("auth.register"),
            data: $registeredUser,
        );
    }

    /**
     * Logout
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        return $this->successResponse(__("auth.logout_success"));
    }
}
