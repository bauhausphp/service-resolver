<?php

namespace Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector;

use Bauhaus\ServiceResolver\Definition;
use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class CircularDependencySafeDefinition implements Definition
{
    private bool $alreadyCalled = false;

    public function __construct(
        private Definition $decorated,
    ) {
    }

    /**
     * @throws CircularDependencyDetected
     */
    public function evaluate(PsrContainer $psrContainer): object
    {
        $this->detectCircularReference();
        $this->markAsAlreadyCalled();

        return $this->decorated->evaluate($psrContainer);
    }

    private function detectCircularReference(): void
    {
        if ($this->alreadyCalled) {
            throw new CircularDependencyDetected();
        }
    }

    private function markAsAlreadyCalled(): void
    {
        $this->alreadyCalled = true;
    }
}
