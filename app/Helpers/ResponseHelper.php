<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ResponseHelper
{
    /**
     * Create success response
     *
     * @param mixed $data Data to be returned
     * @param string $message Success message
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Create error response
     *
     * @param string $message Error message
     * @param mixed $errors Validation errors or additional error data
     * @param int $statusCode HTTP status code
     * @param string|null $logPrefix Prefix for error logging
     * @return JsonResponse
     */
    public static function error(string $message, $errors = null, int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, ?string $logPrefix = null): JsonResponse
    {
        if ($statusCode >= 500 && $logPrefix) {
            $logMessage = "[{$logPrefix}]: {$message}";
            env('APP_ENV') === 'production' ? Log::error($logMessage) : Log::debug($logMessage);
        }

        $response = [
            'status' => 'error',
            'message' => $message,
            'data' => []
        ];

        if ($statusCode === Response::HTTP_UNPROCESSABLE_ENTITY && $errors) {
            $response['errors'] = $errors;
        } elseif ($errors) {
            $response['data'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Created response (201)
     *
     * @param mixed $data Data of created resource
     * @param string $message Success message
     * @return JsonResponse
     */
    public static function created($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return self::success($data, $message, Response::HTTP_CREATED);
    }

    /**
     * No content response (204)
     *
     * @return JsonResponse
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Bad request response (400)
     *
     * @param string $message Error message
     * @param mixed $errors Additional error data
     * @param string|null $logPrefix Prefix for error logging
     * @return JsonResponse
     */
    public static function badRequest(string $message = 'Bad request', $errors = null, ?string $logPrefix = null): JsonResponse
    {
        return self::error($message, $errors, Response::HTTP_BAD_REQUEST, $logPrefix);
    }

    /**
     * Unauthorized response (401)
     *
     * @param string $message Error message
     * @param string|null $logPrefix Prefix for error logging
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized', ?string $logPrefix = null): JsonResponse
    {
        return self::error($message, null, Response::HTTP_UNAUTHORIZED, $logPrefix);
    }

    /**
     * Forbidden response (403)
     *
     * @param string $message Error message
     * @param string|null $logPrefix Prefix for error logging
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden', ?string $logPrefix = null): JsonResponse
    {
        return self::error($message, null, Response::HTTP_FORBIDDEN, $logPrefix);
    }

    /**
     * Not found response (404)
     *
     * @param string $message Error message
     * @param string|null $logPrefix Prefix for error logging
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found', ?string $logPrefix = null): JsonResponse
    {
        return self::error($message, null, Response::HTTP_NOT_FOUND, $logPrefix);
    }

    /**
     * Validation error response (422)
     *
     * @param array $errors Validation errors
     * @param string $message Error message
     * @param string|null $logPrefix Prefix for error logging
     * @return JsonResponse
     */
    public static function validationError(array $errors, string $message = 'Validation failed', ?string $logPrefix = null): JsonResponse
    {
        return self::error($message, $errors, Response::HTTP_UNPROCESSABLE_ENTITY, $logPrefix);
    }

    /**
     * Server error response (500)
     *
     * @param string $message Error message
     * @param \Exception|null $exception The exception that occurred
     * @param string|null $logPrefix Prefix for error logging
     * @return JsonResponse
     */
    public static function serverError(string $message = 'Server error', ?\Exception $exception = null, ?string $logPrefix = null): JsonResponse
    {
        $prefix = $logPrefix ?? 'SERVER';

        if ($exception) {
            $logMessage = "[{$prefix}]: {$exception->getMessage()} in {$exception->getFile()}:{$exception->getLine()}";
            env('APP_ENV') === 'production' ? Log::error($logMessage) : Log::debug($logMessage);
        }

        return self::error($message, null, Response::HTTP_INTERNAL_SERVER_ERROR, $prefix);
    }

    public static function manyRequest(string $message = 'Too many requests', ?string $logPrefix = null): JsonResponse
    {
        return self::error($message, null, Response::HTTP_TOO_MANY_REQUESTS, $logPrefix);
    }
}
