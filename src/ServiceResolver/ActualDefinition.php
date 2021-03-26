<?php

namespace Bauhaus\ServiceResolver;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class ActualDefinition implements Definition
{
    private $callable;

    private function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @throws DefinitionCouldNotBeCreated
     */
    public static function create(mixed $from): self
    {
        if (is_string($from)) {
            $from = fn (PsrContainer $c) => $c->get($from);
        }

        if (is_object($from) && !is_callable($from)) {
            $from = fn () => $from;
        }

        return match (is_callable($from)) {
            true => new self($from),
            false => throw new DefinitionCouldNotBeCreated(),
        };
    }

    public function evaluate(PsrContainer $psrContainer): object
    {
        return ($this->callable)($psrContainer);
    }
}
