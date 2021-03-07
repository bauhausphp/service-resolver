<?php

namespace Bauhaus\Doubles;

class DiscoverServiceB
{
    public function __construct(
        private DiscoverServiceA $a,
    ) {}
}
