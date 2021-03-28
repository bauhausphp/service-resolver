<?php

namespace Bauhaus\ServiceResolver;

use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;

/**
 * @internal
 */
final class DefinitionNotFound extends ServiceCouldNotBeResolved implements PsrNotFoundException
{
    protected function mainMessage(): string
    {
        return 'Service not found';
    }
}
