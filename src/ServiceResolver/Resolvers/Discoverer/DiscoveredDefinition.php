<?php

namespace Bauhaus\ServiceResolver\Resolvers\Discoverer;

use Bauhaus\ServiceResolver\ActualDefinition;
use Bauhaus\ServiceResolver\Definition;
use Psr\Container\ContainerInterface as PsrContainer;
use ReflectionClass as RClass;
use ReflectionParameter as RParam;

/**
 * @internal
 */
final class DiscoveredDefinition implements Definition
{
    private string $id;
    private ActualDefinition $definition;

    private function __construct(string $id)
    {
        if (false === $this->isAClassName($id)) {
            throw DefinitionCouldNotBeDiscovered::idIsNotAClass();
        }

        $this->id = $id;
        $this->discover();
    }

    public static function fromId(string $id): self
    {
        return new self($id);
    }

    private function isAClassName(string $string): bool
    {
        return class_exists($string);
    }

    public function evaluate(PsrContainer $psrContainer): object
    {
        return $this->definition->evaluate($psrContainer);
    }

    private function discover(): void
    {
        $dependencyParams = $this->extractDependencyParams();

        $this->assertParamsCanBeResolved(...$dependencyParams);
        $this->createActualDefinition(...$dependencyParams);
    }

    /**
     * @return RParam[]
     */
    private function extractDependencyParams(): array
    {
        $class = new RClass($this->id);
        $constructor = $class->getConstructor();

        return null === $constructor ? [] : $constructor->getParameters();
    }

    private function assertParamsCanBeResolved(RParam ...$params): void
    {
        $filter = fn (RParam $p): bool => $p->isVariadic() || false === $this->isAClassName($p->getType());

        $unresolvableParams = array_filter($params, $filter);

        if ([] !== $unresolvableParams) {
            throw DefinitionCouldNotBeDiscovered::unresolvableDependencies();
        }
    }

    private function createActualDefinition(RParam ...$depParams): void
    {
        $targetClass = $this->id;
        $depIds = array_map(fn (RParam $p): string => $p->getType(), $depParams);

        $callable = fn (PsrContainer $c): mixed
            => new $targetClass(...array_map(fn (string $id) => $c->get($id), $depIds));

        $this->definition = ActualDefinition::create($callable);
    }
}
