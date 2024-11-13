<?php

namespace App\Trait;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function respondSuccess($message, $data = [], $statusCode = 200): JsonResponse
    {
        $response = [
            'message' => $message,
            'status' => $statusCode
        ];

        if (count($data) > 0)
            $response['data'] = $data;

        return response()->json($response, $statusCode);
    }

    public function respondError($errors = [], $statusCode = null): JsonResponse
    {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode
            ], $statusCode);
        }

        return response()->json([
            'errors' => $errors,
            'status' => $statusCode
        ], $statusCode ?? 500);
    }
}
