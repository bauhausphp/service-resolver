<?php

namespace Bauhaus\ServiceResolver\Resolvers\Discoverer;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Definition;

/**
 * @internal
 */
final class Discoverer implements Resolver
{
    private array $namespaces;

    public function __construct(
        private Resolver $decorated,
        string ...$namespaces,
    ) {
        $this->namespaces = $this->trim(...$namespaces);
    }

    public function get(string $id): ?Definition
    {
        $result = $this->decorated->get($id);

        return match ($result) {
            null => $this->discover($id),
            default => $result,
        };
    }

    private function discover(string $id): ?Definition
    {
        return $this->isAmongNamespaces($id) ? DiscoveredDefinition::fromId($id) : null;
    }

    private function isAmongNamespaces(string $id): bool
    {
        $id = $this->trim($id)[0];

        foreach ($this->namespaces as $namespace) {
            if (str_starts_with($id, $namespace)) {
                return true;
            }
        }

        return false;
    }

    private function trim(string ...$strings): array
    {
        return array_map(fn (string $s) => trim($s, '\\'), $strings);
    }
}
