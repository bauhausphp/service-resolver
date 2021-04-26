<?php

namespace Bauhaus\ServiceResolver;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class ActualDefinition implements Definition
{
    private $callable;

    private function __construct(mixed $callable)
    {
        if (!is_callable($callable)) {
            throw new DefinitionCouldNotBeCreated();
        }

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

        return new self($from);
    }

    public function evaluate(PsrContainer $psrContainer): object
    {
        return ($this->callable)($psrContainer);
    }
}
