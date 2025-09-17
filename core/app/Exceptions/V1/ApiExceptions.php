<?php

namespace App\Exceptions\V1;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptions
{
    /**
     * @var array|string[]
     */
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        NotFoundHttpException::class => 'handleNotFoundException',
        AccessDeniedHttpException::class => 'handleAccessDeniedHttpException',
    ];

    /**
     * @param AuthenticationException $e
     * @param Request $request
     * @return JsonResponse
     */
    public static function handleAuthenticationException(AuthenticationException $e, Request $request): JsonResponse
    {
        $source = 'Line: ' . $e->getLine() . ', File: ' . $e->getFile();
        Log::notice(basename(get_class($e)) . ' - ' . $e->getMessage() . ' - ' . $source);

        return response()->json([
            'errors' => [
                'type' => basename(get_class($e)),
                'status' => 401,
                'message' => $e->getMessage()
            ],
            'status' => 401
        ]);
    }

    /**
     * @param ValidationException $e
     * @param Request $request
     * @return JsonResponse
     */
    public static function handleValidationException(ValidationException $e, Request $request): JsonResponse
    {
        $errors = [];

        foreach ($e->errors() as $key => $value)
            foreach ($value as $message) {
                $errors[] = [
                    'type' => basename(get_class($e)),
                    'status' => 422,
                    'message' => $message,
                ];
            }

        return response()->json([
            'errors' => $errors,
            'status' => 422
        ]);
    }

    /**
     * @param ModelNotFoundException<Model>|NotFoundHttpException $e
     * @param Request $request
     * @return JsonResponse
     */
    public static function handleNotFoundException(ModelNotFoundException|NotFoundHttpException $e, Request $request): JsonResponse
    {
        return response()->json([
            'errors' => [
                'type' => basename(get_class($e)),
                'status' => 404,
                'message' => 'Not Found ' . $request->getRequestUri()
            ],
            'status' => 404
        ]);
    }

    /**
     * @param AccessDeniedHttpException $e
     * @param Request $request
     * @return JsonResponse
     */
    public static function handleAccessDeniedHttpException(AccessDeniedHttpException $e, Request $request): JsonResponse
    {
        return response()->json([
            'errors' => [
                'type' => basename(get_class($e)),
                'status' => 403,
                'message' => 'Access Denied ' . $request->getRequestUri()
            ],
            'status' => 403
        ]);
    }
}
