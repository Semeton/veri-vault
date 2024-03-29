<?php

namespace App\Enums;

class HTTPResponseEnum {
    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const UNAUTHENTICATED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;

    public static function getStatusCodes()
    {
        return [
            self::OK,
            self::CREATED,
            self::BAD_REQUEST,
            self::UNAUTHENTICATED,
            self::FORBIDDEN,
            self::NOT_FOUND,
        ];
    }

    public static function getUnathorizedMessage()
    {
        return [
            'error' => 'Forbidden',
            'message' => 'You are not allowed to perform this operation'
        ];
    }

    public static function getBadRequestMessage()
    {
        return [
            'error' => 'BadRequest',
            'message' => 'There was an error processing the request'
        ];
    }

    public static function getNotFoundMessage(string $request, string $uuid)
    {
        return [
            'error' => 'NotFound',
            'message' => 'Requested resource does not exist',
            'details' => [
                'request' => $request,
                'uuid' => $uuid
            ]
        ];
    }

    public static function getExceptionMessage(string $message)
    {
        return [
                'error' => 'Exception',
                'message' => $message,
            ];
    }
}