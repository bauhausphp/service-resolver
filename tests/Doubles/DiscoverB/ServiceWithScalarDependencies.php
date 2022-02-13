<?php

namespace Bauhaus\Doubles\DiscoverB;

class ServiceWithScalarDependencies
{
    public function __construct(
        private bool $bool,
        private int $int,
        private string $string,
    ) {
    }
}
