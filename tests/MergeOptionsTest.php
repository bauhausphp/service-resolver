<?php

namespace Bauhaus;

use PHPUnit\Framework\TestCase;

class MergeOptionsTest extends TestCase
{
    /**
     * @test
     */
    public function keepValuesFromBothOptions(): void
    {
        $options1 = ServiceResolverOptions::new()
            ->withDefinitionFiles('file11', 'file12')
            ->withDiscoverableNamespaces('namespace11', 'namespace12')
            ->withServices(['service1' => new \StdClass()]);
        $options2 = ServiceResolverOptions::new()
            ->withDefinitionFiles('file21', 'file22')
            ->withDiscoverableNamespaces('namespace21', 'namespace22')
            ->withServices(['service2' => new \StdClass()]);

        $mergedOptions = $options1->mergedWith($options2);

        $expected = ServiceResolverOptions::new()
            ->withDefinitionFiles('file11', 'file12', 'file21', 'file22')
            ->withDiscoverableNamespaces('namespace11', 'namespace12', 'namespace21', 'namespace22')
            ->withServices(['service1' => new \StdClass(), 'service2' => new \StdClass()]);
        self::assertEquals($expected, $mergedOptions);
    }
}
