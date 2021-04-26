<?php

namespace Bauhaus\ServiceResolver;

use Psr\Container\ContainerExceptionInterface as PsrContainerException;

/**
 * @internal
 */
final class ServiceEvaluationError extends ServiceCouldNotBeResolved implements PsrContainerException
{
    protected function mainMessage(): string
    {
        return 'Error while evaluating service';
    }
}
