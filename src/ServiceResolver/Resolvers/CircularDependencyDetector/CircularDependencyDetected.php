<?php

namespace Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector;

use RuntimeException;

/**
 * @internal
 */
final class CircularDependencyDetected extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Circular dependency detected');
    }
}
