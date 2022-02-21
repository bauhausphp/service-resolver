<?php

namespace Bauhaus\ServiceResolver;

use Psr\Container\ContainerInterface as PsrContainer;
use Throwable;

/**
 * @internal
 */
interface Definition
{
    /**
     * @throws Throwable
     */
    public function load(PsrContainer $psrContainer): object;
}
