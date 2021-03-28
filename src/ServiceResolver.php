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
            throw new DefinitionNotFound($id, $reason);
        }

        // TODO Catch throwable and throw proper exception
        // TODO Create exception with good error message
        //   ServiceA failed because of -> ServiceB failed because of -> Exception

        if (null === $definition) {
            throw new DefinitionNotFound($id);
        }

        return $definition->evaluate($this);
    }

    private function resolve(string $id): ?Definition
    {
        return $this->resolver->get($id);
    }
}
