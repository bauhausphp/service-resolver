<?php

namespace Bauhaus\ServiceResolver\Factory;

use Bauhaus\ServiceResolver;
use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector\CircularDependencyDetector;
use Bauhaus\ServiceResolver\Resolvers\Discoverer\Discoverer;
use Bauhaus\ServiceResolver\Resolvers\MemoryCache\MemoryCache;
use Bauhaus\ServiceResolver\Resolvers\DefinitionCollection;
use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolverOptions;

/**
 * @internal
 */
final class ServiceResolverFactory
{
    private function __construct(
        private ServiceResolverOptions $options,
    ) {
    }

    /**
     * @throws DefinitionLoaderException
     */
    public static function build(ServiceResolverOptions $options): ServiceResolver
    {
        $factory = new self($options);

        $resolver = $factory->createServiceDefinitionsContainer();
        $resolver = $factory->decorateWithDiscoverer($resolver);
        $resolver = $factory->decorateWithCircularDependencyDetector($resolver);
        $resolver = $factory->decorateWithMemoryCache($resolver);

        return new ServiceResolver($resolver);
    }

    private function createServiceDefinitionsContainer(): Resolver
    {
        return new DefinitionCollection($this->loadServiceDefinitions());
    }

    private function decorateWithDiscoverer(Resolver $resolver): Resolver
    {
        return match ($this->options->isDiscoverableEnabled()) {
            false => $resolver,
            true => new Discoverer($resolver, ...$this->options->discoverableNamespaces()),
        };
    }

    private function decorateWithCircularDependencyDetector(Resolver $resolver): Resolver
    {
        return new CircularDependencyDetector($resolver);
    }

    private function decorateWithMemoryCache(Resolver $resolver): Resolver
    {
        return new MemoryCache($resolver);
    }

    /**
     * @return Definition[]
     */
    private function loadServiceDefinitions(): array
    {
        $loader = new DefinitionLoader();
        return $loader->loadFromFiles(...$this->options->definitionFiles());
    }
}
