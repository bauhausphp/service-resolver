<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CircularDependencyDetectorTest extends TestCase
{
    private CircularDependencyDetector $circuparDependecyDetector;
    private Resolver|MockObject $decorated;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(Resolver::class);

        $this->circuparDependecyDetector = new CircularDependencyDetector($this->decorated);
    }

    /**
     * @test
     */
    public function returnResultFromDecorated(): void
    {
        $serviceDefinition = ServiceDefinition::build(fn () => 'fake');
        $this->decorated
            ->method('get')
            ->with('some-id')
            ->willReturn($serviceDefinition);

        $result = $this->circuparDependecyDetector->get('some-id');

        $this->assertSame($serviceDefinition, $result);
    }

    /**
     * @test
     */
    public function throwExceptionIfCircularDependencyIsDetected(): void
    {
        $this->decorated
            ->method('get')
            ->with('some-id')
            ->willReturnCallback(fn(string $id) => $this->circuparDependecyDetector->get($id));

        $this->expectException(CircularDependencyDetected::class);
        $this->expectExceptionMessage('Circular dependency detected: some-id -> some-id');

        $this->circuparDependecyDetector->get('some-id');
    }

    /**
     * @test
     */
    public function doNotFalseDetectCircularDependencyIfSameIdIsCalledMoreThanOnceInDifferentStacks(): void
    {
        $serviceDefinition = ServiceDefinition::build(fn () => 'fake');
        $this->decorated
            ->method('get')
            ->with('some-id')
            ->willReturn($serviceDefinition);

        $this->circuparDependecyDetector->get('some-id');
        $this->circuparDependecyDetector->get('some-id');
        $result = $this->circuparDependecyDetector->get('some-id');

        $this->assertSame($serviceDefinition, $result);
    }
}
