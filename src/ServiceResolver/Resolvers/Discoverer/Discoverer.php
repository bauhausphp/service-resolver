<?php

namespace Bauhaus\ServiceResolver\Resolvers\Discoverer;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Definition;

/**
 * @internal
 */
final class Discoverer implements Resolver
{
    private Resolver $decorated;
    private array $discoverableNamespaces;

    public function __construct(Resolver $decorated, string ...$discoverableNamespaces)
    {
        $this->decorated = $decorated;
        $this->discoverableNamespaces = $this->trim(...$discoverableNamespaces);
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
        return match ($this->withinDiscoverableNamespaces($id)) {
            false => null,
            true => DiscoveredDefinition::fromId($id),
        };
    }

    private function withinDiscoverableNamespaces(string $id): bool
    {
        $id = $this->trim($id)[0];

        foreach ($this->discoverableNamespaces as $namespace) {
            if (str_starts_with($id, $namespace)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    private function trim(string ...$strings): array
    {
        return array_map(fn (string $s) => trim($s, '\\'), $strings);
    }
}
