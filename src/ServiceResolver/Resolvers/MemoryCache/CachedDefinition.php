<?php

namespace Bauhaus\ServiceResolver\Resolvers\MemoryCache;

use Bauhaus\ServiceResolver\Definition;
use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class CachedDefinition implements Definition
{
    private ?object $cache = null;

    public function __construct(
        private Definition $decorated,
    ) {
    }

    public function evaluate(PsrContainer $psrContainer): object
    {
        if (null === $this->cache) {
            $this->cache = $this->decorated->evaluate($psrContainer);
        }

        return $this->cache;
    }
}
