<?php

namespace Bauhaus;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Resolvers\Discoverer;
use Bauhaus\ServiceResolver\Resolvers\MemoryCache;
use Bauhaus\ServiceResolver\Resolvers\ServiceDefinitionContainer;
use Bauhaus\ServiceResolver\ServiceDefinition;
use Bauhaus\ServiceResolver\ServiceDefinitionLoader;
use Bauhaus\ServiceResolver\ServiceDefinitionLoaderException;

final class ServiceResolverFactory
{
    private function __construct(
        private ServiceResolverOptions $options,
    ) {
    }

    /**
     * @throws ServiceDefinitionLoaderException
     */
    public static function build(ServiceResolverOptions $options): ServiceResolver
    {
        $factory = new self($options);

        $resolver = $factory->createServiceDefinitionsContainer();
        $resolver = $factory->createDiscovererLayer($resolver);
        $resolver = $factory->createMemoryCacheLayer($resolver);

        return new ServiceResolver($resolver);
    }

    private function createServiceDefinitionsContainer(): Resolver
    {
        return new ServiceDefinitionContainer($this->loadServiceDefinitions());
    }

    private function createDiscovererLayer(Resolver $resolver): Resolver
    {
        return match ($this->options->isDiscoverableEnabled()) {
            false => $resolver,
            true => new Discoverer($resolver, ...$this->options->discoverableNamespaces()),
        };
    }

    private function createMemoryCacheLayer(Resolver $resolver): Resolver
    {
        return new MemoryCache($resolver);
    }

    /**
     * @return ServiceDefinition[]
     */
    private function loadServiceDefinitions(): array
    {
        $loader = new ServiceDefinitionLoader();
        return $loader->loadFromFiles(...$this->options->definitionFiles());
    }
}
