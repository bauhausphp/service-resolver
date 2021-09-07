<?php

namespace Bauhaus\ServiceResolver\Resolvers\MemoryCache;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Definition;

/**
 * @internal
 */
final class MemoryCache implements Resolver
{
    private array $cache = [];

    public function __construct(
        private Resolver $decorated,
    ) {
    }

    public function get(string $id): ?Definition
    {
        $this->ensureCache($id);

        return $this->cache[$id];
    }

    private function ensureCache(string $id): void
    {
        if ($this->isCached($id)) {
            return;
        }

        $this->createCache($id);
    }

    private function isCached(string $id): bool
    {
        return array_key_exists($id, $this->cache);
    }

    private function createCache(string $id): void
    {
        $result = $this->decorated->get($id);

        $this->cache[$id] = $result ? new CachedDefinition($result) : null;
    }
}
