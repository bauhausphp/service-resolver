<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;

/**
 * @internal
 */
final class CircularDependencyDetector implements Resolver
{
    public function __construct(
        private Resolver $decorated,
    ) {
    }

    public function get($id): ?ServiceDefinition
    {
    }
}
