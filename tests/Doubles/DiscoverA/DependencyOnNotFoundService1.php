<?php

namespace Bauhaus\Doubles\DiscoverA;

use Bauhaus\Doubles\NotFoundService;

class DependencyOnNotFoundService1
{
    public function __construct(
        private NotFoundService $x,
    ) {
    }
}
