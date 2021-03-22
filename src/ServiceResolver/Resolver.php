<?php

namespace Bauhaus\ServiceResolver;

/**
 * @internal
 */
interface Resolver
{
    public function get(string $id): ?ServiceDefinition;
}
