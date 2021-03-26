<?php

namespace Bauhaus\ServiceResolver\Resolvers\MemoryCache;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Definition;

/**
 * @internal
 */
final class MemoryCache implements Resolver
{
    /** @var CachedDefinition[] */
    private array $cache = [];

    public function __construct(
        private Resolver $decorated,
    ) {
    }

    public function get(string $id): ?Definition
    {
        if (false === $this->isCached($id)) {
            $this->createCache($id);
        }

        return $this->retrieveCache($id);
    }

    private function isCached(string $id): bool
    {
        return array_key_exists($id, $this->cache);
    }

    private function createCache(string $id): void
    {
        $result = $this->decorated->get($id);

        $this->cache[$id] = match ($result) {
            null => null,
            default => new CachedDefinition($result)
        };
    }

    private function retrieveCache(string $id): ?Definition
    {
        return $this->cache[$id];
    }
}
