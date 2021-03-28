<?php

namespace Bauhaus\ServiceResolver;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface as PsrContainer;
use StdClass;

class ActualDefinitionTest extends TestCase
{
    private PsrContainer|MockObject $psrContainer;

    protected function setUp(): void
    {
        $this->psrContainer = $this->createMock(PsrContainer::class);
    }

    /**
     * @test
     */
    public function loadFromProvidedCallable(): void
    {
        $this->psrContainer
            ->expects($this->never())
            ->method('get');

        $definition = ActualDefinition::create(fn () => new StdClass());
        $result = $definition->evaluate($this->psrContainer);

        $this->assertEquals(new StdClass(), $result);
    }

    /**
     * @test
     */
    public function loadFromProvidedCallablePassingPsrContainer(): void
    {
        $service = new StdClass();
        $this->psrContainer
            ->expects($this->once())
            ->method('get')
            ->with('foo')
            ->willReturn($service);

        $definition = ActualDefinition::create(fn (PsrContainer $c) => $c->get('foo'));
        $result = $definition->evaluate($this->psrContainer);

        $this->assertSame($service, $result);
    }

    /**
     * @test
     */
    public function loadByCallingPsrContainerWithProvidedString(): void
    {
        $service = new StdClass();
        $this->psrContainer
            ->expects($this->once())
            ->method('get')
            ->with('foo')
            ->willReturn($service);

        $definition = ActualDefinition::create('foo');
        $result = $definition->evaluate($this->psrContainer);

        $this->assertSame($service, $result);
    }

    /**
     * @test
     */
    public function loadByReturningTheProvidedObject(): void
    {
        $service = new StdClass();
        $this->psrContainer
            ->expects($this->never())
            ->method('get');

        $serviceDefinition = ActualDefinition::create($service);
        $result = $serviceDefinition->evaluate($this->psrContainer);

        $this->assertSame($service, $result);
    }

    /**
     * @test
     */
    public function throwExceptionIfCannotCreateFromProvidedValue(): void
    {
        $this->expectException(DefinitionCouldNotBeCreated::class);

        ActualDefinition::create(null);
    }
}
