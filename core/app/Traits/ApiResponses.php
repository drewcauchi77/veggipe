<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses {

    /**
     * @param $message
     * @param array $data
     * @return JsonResponse
     */
    protected function ok($message, array $data = []): JsonResponse
    {
        return $this->success($message, $data, 200);
    }

    /**
     * @param $message
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function success($message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    /**
     * @param $message
     * @param $statusCode
     * @return JsonResponse
     */
    protected function error($message, $statusCode): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    protected function notAuthorised($message): JsonResponse
    {
        return $this->error([
            'type' => 'Unauthorised',
            'status' => 401,
            'message' => $message,
        ], 401);
    }
}
