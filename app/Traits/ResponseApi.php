<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseApi
{
    /**
     * Response
     *
     * @param string $message
     * @param mixed $data
     * @param [type] $status_code
     * @param boolean $success
     * @return JsonResponse
     */
    public function response(
        string $message,
        mixed $data = null,
        int $status_code = Response::HTTP_OK,
        bool $success = true
    ): JsonResponse {
        $response = [
            "success" => $success,
            "payload" => is_array($data) ? $data : ["data" => $data],
            "message" => $message
        ];

        if (empty($response["payload"])) {
            unset($response["payload"]);
        }

        return response()->json($response, $status_code);
    }

    /**
     * Success Response
     *
     * @param string $message
     * @param mixed $data
     * @param [type] $status_code
     * @return JsonResponse
     */
    public function successResponse(
        string $message,
        mixed $data = null,
        int $status_code = Response::HTTP_OK,
    ): JsonResponse {
        return $this->response(
            message: $message,
            data: $data,
            status_code: $status_code
        );
    }

    /**
     * Error response
     *
     * @param string $message
     * @param [type] $status_code
     * @return JsonResponse
     */
    public function errorResponse(
        string $message,
        int $status_code = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        return $this->response(
            message: $message,
            status_code: $status_code,
            success: false
        );
    }
}
