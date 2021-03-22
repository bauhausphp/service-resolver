<?php

namespace Bauhaus\ServiceResolver;

use RunTimeException;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;

/**
 * @internal
 */
final class ServiceDefinitionNotFound extends RunTimeException implements PsrNotFoundException
{
    public function __construct(string $id)
    {
        parent::__construct("Service definition with id $id was not found");
    }
}
