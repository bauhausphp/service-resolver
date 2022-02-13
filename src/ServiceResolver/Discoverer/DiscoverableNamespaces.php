<?php

namespace Bauhaus\ServiceResolver\Discoverer;

use Bauhaus\ServiceResolverOptions;

/**
 * @internal
 */
final class DiscoverableNamespaces
{
    /** @var string[] */ private readonly array $namespaces;

    public function __construct(ServiceResolverOptions $options)
    {
        $this->namespaces = $options->discoverableNamespaces;
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
