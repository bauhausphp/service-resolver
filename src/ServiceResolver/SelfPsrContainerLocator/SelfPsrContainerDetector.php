<?php

namespace Bauhaus\ServiceResolver\SelfPsrContainerLocator;

use Bauhaus\ServiceResolver\Identifier;
use Bauhaus\ServiceResolver\Locator;
use Bauhaus\ServiceResolver\Definition;
use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final class SelfPsrContainerDetector implements Locator
{
    public function __construct(
        private readonly Locator $actualLocator,
    ) {
    }

    public function find(Identifier $id): ?Definition
    {
        return (string) $id === PsrContainer::class ? new SelfPsrContainerReturner() : $this->actualLocator->find($id);
    }
}
