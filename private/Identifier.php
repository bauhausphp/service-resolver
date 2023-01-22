<?php

namespace Bauhaus\ServiceResolver;

use ReflectionClass as RClass;

/**
 * @internal
 */
final readonly class Identifier
{
    public function __construct(
        private string $value,
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
