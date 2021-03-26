<?php

namespace Bauhaus\ServiceResolver\Resolvers\Discoverer;

use RuntimeException;

/**
 * @internal
 */
final class DefinitionCouldNotBeDiscovered extends RuntimeException
{
    private function __construct(string $reason)
    {
        parent::__construct("Cannot discover definition if: $reason");
    }

    public static function idIsNotAClass(): self
    {
        return new self("id is not a valid class name");
    }

    public static function unresolvableDependencies(): self
    {
        return new self("any of the service dependencies is not a valid class name");
    }
}
