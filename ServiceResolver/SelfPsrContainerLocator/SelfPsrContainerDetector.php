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
        $isPsrContainer = (string) $id === PsrContainer::class;

        return $isPsrContainer ? new SelfPsrContainerReturner() : $this->actualLocator->find($id);
    }
}
