<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

use Bauhaus\Doubles\UndiscoverableService;

class NotFoundMessage1
{
    public function __construct(
        private UndiscoverableService $x,
    ) {
    }
}
