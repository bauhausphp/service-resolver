<?php

namespace Bauhaus;

use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\ServiceEvaluationError;
use Bauhaus\ServiceResolver\ServiceNotFound;
use Bauhaus\ServiceResolver\Factory\ResolverChainFactory;
use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Resolvers\Discoverer\DefinitionCouldNotBeDiscovered;
use Psr\Container\ContainerInterface as PsrContainer;
use Throwable;

final class ServiceResolver implements PsrContainer
{
    private function __construct(
        private Resolver $resolver,
    ) {
    }

    public static function build(ServiceResolverOptions $options): self
    {
        $resolverChain = ResolverChainFactory::build($options);

        return new self($resolverChain);
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
            throw ServiceNotFound::with($id, $reason);
        }

        if (null === $definition) {
            throw ServiceNotFound::with($id);
        }

        try {
            return $definition->evaluate($this);
        } catch (ServiceNotFound $ex) {
            throw ServiceNotFound::fromSelfPrevious($id, $ex);
        } catch (ServiceEvaluationError $ex) {
            throw ServiceEvaluationError::fromSelfPrevious($id, $ex);
        } catch (Throwable $ex) {
            throw ServiceEvaluationError::with($id, $ex);
        }
    }

    private function resolve(string $id): ?Definition
    {
        return $this->resolver->get($id);
    }
}
