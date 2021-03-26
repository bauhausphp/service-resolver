<?php

namespace Bauhaus;

trait DoubleDefinitionTrait
{
    private function definitionPath(string $fileName): string
    {
        return __DIR__ . '/Doubles/' . $fileName;
    }
}
