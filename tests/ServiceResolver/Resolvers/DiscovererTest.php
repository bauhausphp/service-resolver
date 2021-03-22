<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use Bauhaus\Doubles\ServiceWithScalarArrayDependency;
use Bauhaus\Doubles\ServiceWithScalarBoolDependency;
use Bauhaus\Doubles\ServiceWithScalarIntDependency;
use Bauhaus\Doubles\ServiceWithScalarStringDependency;
use Bauhaus\Doubles\ServiceWithVariadicDependency;
use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\ServiceDefinition;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface as PsrContainer;
use StdClass;

class DiscovererTest extends TestCase
{
    private Discoverer $discoverer;
    private Resolver|MockObject $decorated;
    private PsrContainer|MockObject $psrContainer;

    protected function setUp(): void
    {
        $this->psrContainer = $this->createMock(PsrContainer::class);
        $this->decorated = $this->createMock(Resolver::class);

        $this->discoverer = new Discoverer(
            $this->decorated,
            '\\Bauhaus\\Doubles',
        );
    }

    /**
     * @test
     */
    public function returnServiceDefinitionFromDecoratedIfItIsFound(): void
    {
        $serviceDefinition = ServiceDefinition::build(fn () => 'fake');
        $this->decorated
            ->method('get')
            ->with('some-id')
            ->willReturn($serviceDefinition);

        $result = $this->discoverer->get('some-id');

        $this->assertSame($serviceDefinition, $result);
    }

    /**
     * @test
     */
    public function cannotDiscoverServiceIfIdIsNotAClassName(): void
    {
        $this->decorated
            ->method('get')
            ->with('some-id')
            ->willReturn(null);

        $null = $this->discoverer->get('some-id');

        $this->assertNull($null);
    }

    public function undiscoverableClasses(): array
    {
        return [[DateTimeImmutable::class], [StdClass::class]];
    }

    /**
     * @test
     * @dataProvider undiscoverableClasses
     */
    public function doNotDiscoverServiceOutOfDiscoverableNamespaces(string $id): void
    {
        $this->decorated
            ->method('get')
            ->with($id)
            ->willReturn(null);

        $null = $this->discoverer->get($id);

        $this->assertNull($null);
    }

    public function servicesWithUnresolvableDependencies(): array
    {
        return [
            'bool dependency' => [ServiceWithScalarBoolDependency::class],
            'int dependency' => [ServiceWithScalarIntDependency::class],
            'string dependency' => [ServiceWithScalarStringDependency::class],
            'array dependency' => [ServiceWithScalarArrayDependency::class],
            'variadic dependency' => [ServiceWithVariadicDependency::class],
        ];
    }

    /**
     * @test
     * @dataProvider servicesWithUnresolvableDependencies
     */
    public function cannotDiscoverServiceWithScalarDependencies(string $id): void
    {
        $this->decorated
            ->method('get')
            ->with($id)
            ->willReturn(null);

        $null = $this->discoverer->get($id);

        $this->assertNull($null);
    }

    /**
     * @test
     */
    public function discoverServiceDefinitionOfServiceWithoutDependencies(): void
    {
        $this->decorated
            ->method('get')
            ->with(ServiceWithoutDependency::class)
            ->willReturn(null);

        $serviceDefinition = $this->discoverer->get(ServiceWithoutDependency::class);

        $this->assertEquals(
            new ServiceWithoutDependency(),
            $serviceDefinition->load($this->psrContainer)
        );
    }

    /**
     * @test
     */
    public function discoverServiceDefinitionOfServiceWithDependencies(): void
    {
        $this->decorated
            ->method('get')
            ->with(ServiceWithOneDependency::class)
            ->willReturn(null);
        $this->psrContainer
            ->method('get')
            ->with(ServiceWithoutDependency::class)
            ->willReturn(new ServiceWithoutDependency());

        $serviceDefinition = $this->discoverer->get(ServiceWithOneDependency::class);

        $this->assertEquals(
            new ServiceWithOneDependency(new ServiceWithoutDependency()),
            $serviceDefinition->load($this->psrContainer)
        );
    }
}
