<?php

namespace Bauhaus\ServiceResolver\Discoverer\DependencyLoader;

use Bauhaus\ServiceResolver\Discoverer\DiscoveredService;
use Psr\Container\ContainerInterface as PsrContainer;
use ReflectionFunction as RFunc;
use ReflectionParameter as RParam;

/**
 * @internal
 */
final class DependenciesLoader
{
    /** @var LoadableDependency[] */ private readonly array $dependencies;

    private function __construct(RParam ...$rParams)
    {
        $this->dependencies = array_map(fn (RParam $p): LoadableDependency => new LoadableDependency($p), $rParams);
    }

    public static function forDiscoveredService(DiscoveredService $discoveredService): self
    {
        return new self(...$discoveredService->extractConstructorParams());
    }

    public static function forCallable(callable $callable): self
    {
        $rFunc = new RFunc($callable);

        return new self(...$rFunc->getParameters());
    }

    public function load(PsrContainer $psrContainer): array
    {
        return array_map(fn (LoadableDependency $d): object => $d->load($psrContainer), $this->dependencies);
    }
}
