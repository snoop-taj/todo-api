<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core;


use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoEmptyPayloadException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskExistAlreadyException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskDoesNotExistException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoValidateIdNotFoundException;

class ErrorCodeMap
{
    public const MAP = [
        400 => [
            TodoEmptyPayloadException::class => 1400,
            TodoTaskExistAlreadyException::class => 1401,
            TodoTaskDoesNotExistException::class => 1402
        ],
        404 => [],
        500 => [
            TodoValidateIdNotFoundException::class => 1500
        ],
    ];
}