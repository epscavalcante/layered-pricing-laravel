<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(?string $message = 'Not found')
    {
        parent::__construct(
            message: $message,
        );
    }
}
