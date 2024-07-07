<?php

namespace App\Enums;

use App\Traits\EnumHelper;
use Illuminate\Http\Response;

enum HttpStatus: int
{
    use EnumHelper;
    CASE OK = Response::HTTP_OK;
    CASE BAD_REQUEST = Response::HTTP_BAD_REQUEST;
    CASE UNAUTHORIZED = Response::HTTP_UNAUTHORIZED;
    CASE HTTP_FORBIDDEN = Response::HTTP_FORBIDDEN;
    CASE NOT_FOUND = Response::HTTP_NOT_FOUND;
    CASE UNPROCESSABLE_ENTITY = Response::HTTP_UNPROCESSABLE_ENTITY;
    CASE INTERNAL_SERVER_ERROR = Response::HTTP_INTERNAL_SERVER_ERROR;

    public static function txt(int $code): string
    {
        return Response::statusTexts($code) ?? 'Unknown';
    }

    public static function msg(int $code): string
    {
        return self::statusTexts($code) ?? 'Unknown';
    }

    public static function statusTexts(int $code): string
    {
        return match ($code) {
            self::OK->value => 'Operation succeeded.',
            self::BAD_REQUEST->value => 'Operation failed.',
            self::UNAUTHORIZED->value => 'Unauthorized.',
            self::HTTP_FORBIDDEN->value => 'Forbidden.',
            self::NOT_FOUND->value => 'Not Found.',
            self::UNPROCESSABLE_ENTITY->value => 'Validation failed.',
            self::INTERNAL_SERVER_ERROR->value => 'Internal Server Error.',
            default => 'Unknown status code.',
        };
    }
}
