<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses {

    /**
     * @param string $message
     * @param array<mixed> $data
     * @return JsonResponse
     */
    protected function ok(string $message, array $data = []): JsonResponse
    {
        return $this->success($message, $data, 200);
    }

    /**
     * @param string $message
     * @param array<mixed> $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function success(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    /**
     * @param array<mixed> $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function error(array $errors, int $statusCode): JsonResponse
    {
        return response()->json([
            'errors' => $errors,
            'status' => $statusCode
        ], $statusCode);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function notAuthorised(string $message): JsonResponse
    {
        return $this->error([
            'type' => 'Unauthorised',
            'status' => 401,
            'message' => $message,
        ], 401);
    }
}
