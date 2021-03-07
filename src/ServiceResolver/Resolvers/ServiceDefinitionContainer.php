<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;

/**
 * @internal
 */
final class ServiceDefinitionContainer implements Resolver
{
    /**
     * @param ServiceDefinition[] $serviceDefinitions
     */
    public function __construct(
        private array $serviceDefinitions,
    ) {}

    public function get(string $id): ?ServiceDefinition
    {
        return $this->serviceDefinitions[$id] ?? null;
    }
}