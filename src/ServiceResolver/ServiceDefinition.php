<?php

namespace Bauhaus\ServiceResolver;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class ServiceDefinition
{
    private $callable;

    private function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @throws ServiceDefinitionCouldNotBeBuild
     */
    public static function build(mixed $from): self
    {
        if (is_string($from)) {
            $from = fn (PsrContainer $c) => $c->get($from);
        }

        if (is_object($from) && !is_callable($from)) {
            $from = fn () => $from;
        }

        return match (is_callable($from)) {
            true => new self($from),
            false => throw new ServiceDefinitionCouldNotBeBuild(),
        };
    }

    public function load(PsrContainer $psrContainer): object
    {
        return ($this->callable)($psrContainer);
    }
}