<?php

namespace Bauhaus\Doubles;

class DiscoverServiceC
{
    public function __construct(
        private DiscoverServiceA $a,
        private DiscoverServiceB $b,
    ) {}
}
