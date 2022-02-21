<?php

namespace Bauhaus\ServiceResolver\Discoverer;

use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\Discoverer\DependencyLoader\DependenciesLoader;
use Bauhaus\ServiceResolver\Identifier;
use InvalidArgumentException;
use Psr\Container\ContainerInterface as PsrContainer;
use ReflectionClass as RClass;

/**
 * @internal
 */
final class DiscoveredService implements Definition
{
    private RClass $rClass;

    private function __construct(Identifier $id)
    {
        $this->rClass = $id->isClassName() ? $id->reflectionClass() : throw new InvalidArgumentException();
    }

    public static function fromIdentifier(Identifier $id): self
    {
        return new self($id);
    }

    public function isAmong(DiscoverableNamespaces $namespaces): bool
    {
        return $namespaces->contain($this->rClass->getName());
    }

    public function load(PsrContainer $psrContainer): object
    {
        $dependencies = $this->loadDependencies($psrContainer);

        return $this->rClass->newInstanceArgs($dependencies);
    }

    public function extractConstructorParams(): array
    {
        $constructor = $this->rClass->getConstructor();

        return null === $constructor ? [] : $constructor->getParameters();
    }

    private function loadDependencies(PsrContainer $psrContainer): array
    {
        return DependenciesLoader::forDiscoveredService($this)->load($psrContainer);
    }
}
