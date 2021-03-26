<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\Resolvers\MemoryCache\CachedDefinition;
use Bauhaus\ServiceResolver\Resolvers\MemoryCache\MemoryCache;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MemoryCacheTest extends TestCase
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
        $actualDefinition = $this->createMock(Definition::class);
        $this->decorated
            ->expects($this->once())
            ->method('get')
            ->with('some-id')
            ->willReturn($actualDefinition);

        $result = $this->memoryCache->get('some-id');

        $expected = new CachedDefinition($actualDefinition);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function callDecoratedResolverOnlyOnceAndCacheResult(): void
    {
        $actualDefinition = $this->createMock(Definition::class);
        $this->decorated
            ->expects($this->once())
            ->method('get')
            ->with('some-id')
            ->willReturn($actualDefinition);

        $firstResult = $this->memoryCache->get('some-id');
        $secondResult = $this->memoryCache->get('some-id');

        $this->assertSame($secondResult, $firstResult);
    }
}
