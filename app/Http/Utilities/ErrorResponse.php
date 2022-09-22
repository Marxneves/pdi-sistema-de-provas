<?php

namespace App\Http\Utilities;


class ErrorResponse
{
    public static function item(\Exception $exception): array
    {
        return [
            'error' => true,
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ];
    }
}