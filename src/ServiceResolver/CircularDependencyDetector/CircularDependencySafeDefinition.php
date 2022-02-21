<?php

namespace Bauhaus\ServiceResolver\CircularDependencyDetector;

use Bauhaus\ServiceResolver\Definition;
use LogicException;
use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class CircularDependencySafeDefinition implements Definition
{
    private bool $alreadyCalled = false;

    public function __construct(
        private readonly Definition $actualDefinition,
    ) {
    }

    public function load(PsrContainer $psrContainer): object
    {
        $this->ensureWasNotAlreadyCalled();
        $this->markAsAlreadyCalled();

        return $this->actualDefinition->load($psrContainer);
    }

    private function ensureWasNotAlreadyCalled(): void
    {
        if ($this->alreadyCalled) {
            throw new LogicException('Circular dependency detected');
        }
    }

    private function markAsAlreadyCalled(): void
    {
        $this->alreadyCalled = true;
    }
}
