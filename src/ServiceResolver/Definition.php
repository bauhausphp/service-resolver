<?php

namespace Bauhaus\ServiceResolver;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
interface Definition
{
    public function evaluate(PsrContainer $psrContainer): object;
}
