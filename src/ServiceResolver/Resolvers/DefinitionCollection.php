<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Definition;

/**
 * @internal
 */
final class DefinitionCollection implements Resolver
{
    /**
     * @param Definition[] $definitions
     */
    public function __construct(
        private array $definitions,
    ) {
    }

    public function get(string $id): ?Definition
    {
        return $this->definitions[$id] ?? null;
    }
}
