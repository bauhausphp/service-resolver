<?php

namespace Bauhaus\ServiceResolver;

use LogicException;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;

/**
 * @internal
 */
final class DefinitionNotFound extends LogicException implements PsrNotFoundException
{
    public function __construct(
        private readonly string $serviceId
    ) {
        parent::__construct("No definition found with id {$this->serviceId}");
    }
}
