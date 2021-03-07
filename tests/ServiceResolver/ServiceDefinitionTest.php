<?php

namespace Bauhaus\ServiceResolver;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface as PsrContainer;
use StdClass;

class ServiceDefinitionTest extends TestCase
{
    private PsrContainer $psrContainer;

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
        $serviceDefinition = ServiceDefinition::build(fn () => new StdClass());

        $result = $serviceDefinition->load($this->psrContainer);

        $this->assertEquals(new StdClass(), $result);
    }

    /**
     * @test
     */
    public function loadFromProvidedCallablePassingPsrContainer(): void
    {
        $this->psrContainer
            ->expects($this->once())
            ->method('get')
            ->with(StdClass::class)
            ->willReturn(new StdClass());
        $serviceDefinition = ServiceDefinition::build(
            fn (PsrContainer $c) => $c->get(StdClass::class),
        );

        $result = $serviceDefinition->load($this->psrContainer);

        $this->assertEquals(new StdClass(), $result);
    }

    /**
     * @test
     */
    public function loadByPassingProvidedStringToPsrContainer(): void
    {
        $this->psrContainer
            ->expects($this->once())
            ->method('get')
            ->with(StdClass::class)
            ->willReturn(new StdClass());
        $serviceDefinition = ServiceDefinition::build(StdClass::class);

        $result = $serviceDefinition->load($this->psrContainer);

        $this->assertEquals(new StdClass(), $result);
    }

    /**
     * @test
     */
    public function loadByReturningTheProvidedObject(): void
    {
        $this->psrContainer
            ->expects($this->never())
            ->method('get');
        $serviceDefinition = ServiceDefinition::build(new StdClass());

        $result = $serviceDefinition->load($this->psrContainer);

        $this->assertEquals(new StdClass(), $result);
    }

    /**
     * @test
     */
    public function throwExceptionIfCannotBuildFromProvidedValue(): void
    {
        $this->expectException(ServiceDefinitionCouldNotBeBuild::class);

        ServiceDefinition::build(null);
    }
}
