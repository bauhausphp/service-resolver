<?php

namespace Bauhaus;

use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\DefinitionNotFound;
use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Resolvers\Discoverer\DefinitionCouldNotBeDiscovered;
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
        try {
            return null !== $this->resolve($id);
        } catch (DefinitionCouldNotBeDiscovered) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $id)
    {
        try {
            $definition = $this->resolve($id);
        } catch (DefinitionCouldNotBeDiscovered $reason) {
            throw DefinitionNotFound::with($id, $reason);
        }


        if (null === $definition) {
            throw DefinitionNotFound::with($id);
        }

        try {
            return $definition->evaluate($this);
        } catch (DefinitionNotFound $ex) {
            throw DefinitionNotFound::fromPreviousNotFound($id, $ex);
        }
    }

    private function resolve(string $id): ?Definition
    {
        return $this->resolver->get($id);
    }
}
