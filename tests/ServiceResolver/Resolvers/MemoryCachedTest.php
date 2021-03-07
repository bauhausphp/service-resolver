<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MemoryCachedTest extends TestCase
{
    private MemoryCache $memoryCache;
    private Resolver|MockObject $decorated;

    protected function setUp(): void
    {
        $this->decorated = $this->createMock(Resolver::class);
        $this->memoryCache = new MemoryCache($this->decorated);
    }

    /**
     * @test
     */
    public function forwardCallToDecoratedResolver(): void
    {
        $serviceDefinition = ServiceDefinition::build(fn() => 'fake');
        $this->decorated
            ->expects($this->once())
            ->method('get')
            ->with('some-id')
            ->willReturn($serviceDefinition);

        $result = $this->memoryCache->get('some-id');

        $this->assertSame($serviceDefinition, $result);
    }

    /**
     * @test
     */
    public function callDecoratedResolverOnlyOnce(): void
    {
        $serviceDefinition = ServiceDefinition::build(fn() => 'fake');
        $this->decorated
            ->expects($this->once())
            ->method('get')
            ->with('some-id')
            ->willReturn($serviceDefinition);

        $this->memoryCache->get('some-id');
        $this->memoryCache->get('some-id');
        $this->memoryCache->get('some-id');
        $result = $this->memoryCache->get('some-id');

        $this->assertSame($serviceDefinition, $result);
    }
}
