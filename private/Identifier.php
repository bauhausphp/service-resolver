<?php

namespace Bauhaus\ServiceResolver;

use ReflectionClass as RClass;

/**
 * @internal
 */
final class Identifier
{
    public function __construct(
        private readonly string $value,
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isClassName(): bool
    {
        return class_exists($this) && !$this->reflectionClass()->isAbstract();
    }

    public function reflectionClass(): RClass
    {
        return new RClass((string) $this);
    }
}
