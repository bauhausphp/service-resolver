<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;

/**
 * @internal
 */
final class CircularDependencyDetector implements Resolver
{
    private Resolver $decorated;
    private array $idCallsStack = [];

    public function __construct(Resolver $decorated)
    {
        $this->decorated = $decorated;
    }

    public function get(string $id): ?ServiceDefinition
    {
        $this->stackUp($id);

        if ($this->circularCallDetected()) {
            throw new CircularDependencyDetected($this->idCallsStack);
        }

        $result = $this->decorated->get($id);
        $this->takeLastStackedOut();

        return $result;
    }

    private function circularCallDetected(): bool
    {
        return array_unique($this->idCallsStack) !== $this->idCallsStack;
    }

    private function stackUp(string $id): void
    {
        $this->idCallsStack[] = $id;
    }

    private function takeLastStackedOut(): void
    {
        array_pop($this->idCallsStack);
    }
}
