<?php

namespace Bauhaus\ServiceResolver\Container;

use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\Discoverer\DependencyLoader\DependenciesLoader;
use InvalidArgumentException;
use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class ProvidedService implements Definition
{
    /** @var callable */ private $service;

    private function __construct(callable $service)
    {
        $this->service = $service;
    }

    public static function create(mixed $service): self
    {
        if (is_callable($service)) {
            return new self($service);
        }

        if (is_string($service)) {
            return new self(fn(PsrContainer $c) => $c->get($service));
        }

        if (is_object($service)) {
            return new self(fn() => $service);
        }

        throw new InvalidArgumentException('Invalid service provided');
    }

    public function load(PsrContainer $psrContainer): object
    {
        $dependencies = $this->loadDependencies($psrContainer);

        return ($this->service)(...$dependencies);
    }

    private function loadDependencies(PsrContainer $psrContainer): array
    {
        return DependenciesLoader::forCallable($this->service)->load($psrContainer);
    }
}
