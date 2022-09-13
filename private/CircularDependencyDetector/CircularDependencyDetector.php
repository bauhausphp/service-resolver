<?php

namespace Bauhaus\ServiceResolver\CircularDependencyDetector;

use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\Identifier;
use Bauhaus\ServiceResolver\Locator;

/**
 * @internal
 */
final class CircularDependencyDetector implements Locator
{
    public function __construct(
        private readonly Locator $actualLocator,
    ) {
    }

    public function find(Identifier $id): ?Definition
    {
        $definition = $this->actualLocator->find($id);

        return $definition ? new CircularDependencySafeDefinition($definition) : null;
    }
}
