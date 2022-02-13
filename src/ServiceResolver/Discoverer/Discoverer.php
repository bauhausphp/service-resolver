<?php

namespace Bauhaus\ServiceResolver\Discoverer;

use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\Identifier;
use Bauhaus\ServiceResolver\Locator;
use Bauhaus\ServiceResolverOptions;
use InvalidArgumentException;

/**
 * @internal
 */
final class Discoverer implements Locator
{
    private readonly DiscoverableNamespaces $namespaces;

    public function __construct(
        ServiceResolverOptions $options,
        private readonly Locator $actualLocator,
    ) {
        $this->namespaces = new DiscoverableNamespaces($options);
    }

    public function find(Identifier $id): ?Definition
    {
        return $this->actualLocator->find($id) ?? $this->tryToDiscover($id);
    }

    private function tryToDiscover(Identifier $id): ?Definition
    {
        try {
            $discoveredService = DiscoveredService::fromIdentifier($id);
        } catch (InvalidArgumentException) {
            return null;
        }

        return $discoveredService->isAmong($this->namespaces) ? $discoveredService : null;
    }
}
