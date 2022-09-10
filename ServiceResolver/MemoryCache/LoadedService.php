<?php

namespace Bauhaus\ServiceResolver\MemoryCache;

use Bauhaus\ServiceResolver\Definition;
use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class LoadedService implements Definition
{
    private object $cache;

    public function __construct(
        private readonly Definition $actualDefinition
    ) {
    }

    public function load(PsrContainer $psrContainer): object
    {
        return $this->cache ??= $this->actualDefinition->load($psrContainer);
    }
}
