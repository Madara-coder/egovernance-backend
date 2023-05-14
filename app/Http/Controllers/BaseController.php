<?php

namespace App\Http\Controllers;

use App\Traits\ResponseApi;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    use ResponseApi;

    /**
     * Handles exception
     *
     * @param object $exception
     * @return JsonResponse
     */
    public function handleException(object $exception): JsonResponse
    {
        return $this->errorResponse(
            message: $exception->getMessage(),
        );
    }
}
