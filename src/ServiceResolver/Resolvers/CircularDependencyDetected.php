<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use RuntimeException;

/**
 * @internal
 */
final class CircularDependencyDetected extends RuntimeException
{
    public function __construct(array $stack)
    {
        parent::__construct($this->buildMessage($stack));
    }

    private function buildMessage(array $stack): string
    {
        $stack = implode(' -> ', $stack);

        return "Circular dependency detected: $stack";
    }
}
