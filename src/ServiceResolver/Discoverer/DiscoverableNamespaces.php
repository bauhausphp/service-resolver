<?php

namespace Bauhaus\ServiceResolver\Discoverer;

use Bauhaus\ServiceResolverSettings;

/**
 * @internal
 */
final class DiscoverableNamespaces
{
    /** @var string[] */ private readonly array $namespaces;

    public function __construct(ServiceResolverSettings $settings)
    {
        $this->namespaces = $settings->discoverableNamespaces;
    }

    public function contain(string $className): bool
    {
        foreach ($this->namespaces as $namespace) {
            if (str_starts_with($className, $namespace)) {
                return true;
            }
        }

        return false;
    }
}
