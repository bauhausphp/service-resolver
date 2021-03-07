<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;

/**
 * @internal
 */
final class MemoryCache implements Resolver
{
    private array $cache = [];

    public function __construct(
        private Resolver $decorated,
    ) {}

    public function get(string $id): ?ServiceDefinition
    {
        if (array_key_exists($id, $this->cache)) {
            return $this->cache[$id];
        }

        return $this->cache[$id] = $this->decorated->get($id);
    }
}