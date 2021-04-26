<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector\CircularDependencyDetector;
use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector\CircularDependencySafeDefinition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CircularDependencyDetectorTest extends TestCase
{
    private CircularDependencyDetector $detector;
    private Resolver|MockObject $decorated;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(Resolver::class);
        $this->detector = new CircularDependencyDetector($this->decorated);
    }

    /**
     * @test
     */
    public function makeDefinitionCircularSafe(): void
    {
        $definition = $this->createMock(Definition::class);
        $this->decorated
            ->method('get')
            ->with('some-id')
            ->willReturn($definition);

        $result = $this->detector->get('some-id');

        $expected = new CircularDependencySafeDefinition($definition);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function doNotMakeDefinitionCircularSafeIfResultIsNull(): void
    {
        $this->decorated
            ->method('get')
            ->with('some-id')
            ->willReturn(null);

        $null = $this->detector->get('some-id');

        $this->assertNull($null);
    }
}
