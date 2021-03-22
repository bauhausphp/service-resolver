<?php

namespace Bauhaus;

use Bauhaus\ServiceResolver\ServiceDefinitionNotFound;
use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;
use StdClass;

class ServiceResolverTest extends TestCase
{
    private ServiceResolver $resolver;
    private Resolver|MockObject $resolverChain;

    protected function setUp(): void
    {
        $this->resolverChain = $this->createMock(Resolver::class);
        $this->resolver = new ServiceResolver($this->resolverChain);
    }

    /**
     * @test
     */
    public function hasReturnTrueIfResolverChainReturnsServiceDefinition(): void
    {
        $serviceDefinition = ServiceDefinition::build(fn () => 'who cares?');
        $this->resolverChain
            ->expects($this->once())
            ->method('get')
            ->with('some-id')
            ->willReturn($serviceDefinition);

        $result = $this->resolver->has('some-id');

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function hasReturnFalseIfResolverChainReturnsNull(): void
    {
        $this->resolverChain
            ->expects($this->once())
            ->method('get')
            ->with('some-id')
            ->willReturn(null);

        $result = $this->resolver->has('some-id');

        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function throwNotFoundExceptionIfResolverChainReturnsNull(): void
    {
        $this->resolverChain
            ->expects($this->once())
            ->method('get')
            ->with('some-id')
            ->willReturn(null);

        $this->expectException(ServiceDefinitionNotFound::class);
        $this->expectException(PsrNotFoundException::class);
        $this->expectExceptionMessage('Service definition with id \'some-id\' was not found');

        $this->resolver->get('some-id');
    }

    /**
     * @test
     */
    public function loadServiceIfResolverChainReturnsItsDefinition(): void
    {
        $serviceDefinition = ServiceDefinition::build(fn () => new StdClass());
        $this->resolverChain
            ->expects($this->once())
            ->method('get')
            ->with('some-id')
            ->willReturn($serviceDefinition);

        $service = $this->resolver->get('some-id');

        $this->assertEquals(new StdClass(), $service);
    }
}
