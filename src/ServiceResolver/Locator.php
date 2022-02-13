<?php

namespace Bauhaus\ServiceResolver;

/**
 * @internal
 */
interface Locator
{
    public function find(Identifier $id): ?Definition;
}
