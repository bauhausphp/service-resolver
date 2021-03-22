<?php

namespace Bauhaus;

use Bauhaus\ServiceResolver\ServiceDefinition;
use Bauhaus\ServiceResolver\ServiceDefinitionNotFound;
use Bauhaus\ServiceResolver\Resolver;
use Psr\Container\ContainerInterface as PsrContainer;

final class ServiceResolver implements PsrContainer
{
    public function __construct(
        private Resolver $resolver,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $id): bool
    {
        return null !== $this->resolve($id);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $id)
    {
        $serviceDefinition = $this->resolve($id);

        return match ($serviceDefinition) {
            null => throw new ServiceDefinitionNotFound($id),
            default => $serviceDefinition->load($this),
        };
    }

    private function resolve(string $id): ?ServiceDefinition
    {
        return $this->resolver->get($id);
    }
}
