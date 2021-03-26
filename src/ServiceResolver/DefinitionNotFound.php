<?php

namespace Bauhaus\ServiceResolver;

use RunTimeException;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;
use Throwable;

/**
 * @internal
 */
final class DefinitionNotFound extends RunTimeException implements PsrNotFoundException
{
    public function __construct(string $id, Throwable $previous = null)
    {
        parent::__construct(
            message: "Definition with id '$id' was not found",
            previous: $previous,
        );
    }
}
