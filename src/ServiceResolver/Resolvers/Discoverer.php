<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;
use Psr\Container\ContainerInterface as PsrContainer;
use ReflectionClass;
use ReflectionParameter;

/**
 * @internal
 */
final class Discoverer implements Resolver
{
    private Resolver $decorated;
    private array $discoverableNamespaces;

    public function __construct(
        Resolver $decorated,
        string ...$discoverableNamespaces,
    ) {
        $this->decorated = $decorated;
        $this->discoverableNamespaces = array_map(
            fn (string $n) => trim($n, '\\'),
            $discoverableNamespaces
        );
    }

    public function get($id): ?ServiceDefinition
    {
        $result = $this->decorated->get($id);

        return match ($result) {
            null => $this->discover($id),
            default => $result,
        };
    }

    private function discover(string $id): ?ServiceDefinition
    {
        return match ($this->isDiscoverable($id)) {
            false => null,
            true => $this->createServiceDefinition($id),
        };
    }

    private function isDiscoverable(string $id): bool
    {
        if (!class_exists($id)) {
            return false;
        }

        foreach ($this->discoverableNamespaces as $namespace) {
            if (str_starts_with($id, $namespace)) {
                return true;
            }
        }

        return false;
    }

    private function createServiceDefinition(string $id): ?ServiceDefinition
    {
        $params = $this->extractConstructorParams($id);

        $unresolvableParams = array_filter(
            $params,
            fn (ReflectionParameter $p) => $this->cannotBeResolved($p),
        );

        if ([] !== $unresolvableParams) {
            return null;
        }

        return ServiceDefinition::build($this->buildCallable($id, ...$params));
    }

    /**
     * @return ReflectionParameter[]
     */
    private function extractConstructorParams(string $id): array
    {
        $class = new ReflectionClass($id);
        $constructor = $class->getConstructor();

        return null === $constructor ? [] : $constructor->getParameters();
    }

    private function cannotBeResolved(ReflectionParameter $p): bool
    {
        $scalarTypes = ['bool', 'int', 'string', 'array'];
        $isScalarType = in_array($p->getType(), $scalarTypes);

        return $p->isVariadic() || $isScalarType;
    }

    private function buildCallable(string $id, ReflectionParameter ...$params): callable
    {
        $params = array_map(fn ($p): string => $p->getType(), $params);

        return function (PsrContainer $c) use ($id, $params) {
            $deps = array_map(fn (string $dep) => $c->get($dep), $params);
            return new $id(...$deps);
        };
    }
}
