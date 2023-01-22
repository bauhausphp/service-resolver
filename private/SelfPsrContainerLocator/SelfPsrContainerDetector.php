<?php

namespace Bauhaus\ServiceResolver\SelfPsrContainerLocator;

use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\Identifier;
use Bauhaus\ServiceResolver\Locator;
use Psr\Container\ContainerInterface as PsrContainer;

/**
 * @internal
 */
final readonly class SelfPsrContainerDetector implements Locator
{
    public function __construct(
        private Locator $actualLocator,
    ) {
    }

    public function find(Identifier $id): ?Definition
    {
        $isPsrContainer = (string) $id === PsrContainer::class;

        return $isPsrContainer ? new SelfPsrContainerReturner() : $this->actualLocator->find($id);
    }
}
