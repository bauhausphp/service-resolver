<?php

namespace Bauhaus\ServiceResolver\MemoryCache;

use Bauhaus\ServiceResolver\Identifier;
use Bauhaus\ServiceResolver\Locator;
use Bauhaus\ServiceResolver\Definition;

/**
 * @internal
 */
final class MemoryCache implements Locator
{
    private array $cache = [];

    public function __construct(
        private Locator $actualLocator,
    ) {
    }

    public function find(Identifier $id): ?Definition
    {
        $this->ensureCache($id);

        return $this->cache[(string) $id];
    }

    private function ensureCache(Identifier $id): void
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

    private function createCache(Identifier $id): void
    {
        $definition = $this->actualLocator->find($id);

        $this->cache[(string) $id] = match ($definition) {
            null => null,
            default => new LoadedService($definition),
        };
    }
}
