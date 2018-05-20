<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core;


class ErrorCodeMapper
{
    private const UNKNOWN_HTTP_CODE = 500;
    private const UNKNOWN_ERROR_CODE = 99999;

    /**
     * @param string $name
     * @return int
     */
    public static function getHttpCode(string $name): int
    {
        foreach (ErrorCodeMap::MAP as $httpCode => $exception) {
            if (array_key_exists($name, $exception)) {
                return $httpCode;
            }
        }

        return self::UNKNOWN_HTTP_CODE;
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getErrorCode(string $name): int
    {
        return ErrorCodeMap::MAP[self::getHttpCode($name)][$name] ?? self::UNKNOWN_ERROR_CODE;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function isKnownError(string $name): bool
    {
        return self::getErrorCode($name) !== self::UNKNOWN_ERROR_CODE;
    }
}