<?php

namespace Bauhaus\ServiceResolver\SelfPsrContainerLocator;

use Bauhaus\ServiceResolver\Definition;
use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class SelfPsrContainerReturner implements Definition
{
    public function load(PsrContainer $psrContainer): object
    {
        return $psrContainer;
    }
}
