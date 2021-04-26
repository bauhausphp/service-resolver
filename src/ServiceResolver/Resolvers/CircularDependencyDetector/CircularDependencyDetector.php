<?php

namespace Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Definition;

/**
 * @internal
 */
final class CircularDependencyDetector implements Resolver
{
    public function __construct(
        private Resolver $decorated,
    ) {
    }

    public function get(string $id): ?Definition
    {
        $result = $this->decorated->get($id);

        if (null === $result) {
            return null;
        }

        return new CircularDependencySafeDefinition($result);
    }
}
