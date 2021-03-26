<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarArrayDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarBoolDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarIntDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarStringDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithVariadicDependency;
use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use Bauhaus\ServiceResolver\Resolver;
use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\Resolvers\Discoverer\DefinitionCouldNotBeDiscovered;
use Bauhaus\ServiceResolver\Resolvers\Discoverer\Discoverer;
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
    public function returnDefinitionFromDecoratedIfItIsFound(): void
    {
        $definition = $this->createMock(Definition::class);
        $this->decorated
            ->method('get')
            ->with('some-id')
            ->willReturn($definition);

        $result = $this->discoverer->get('some-id');

        $this->assertSame($definition, $result);
    }

    public function classesOutOfDiscoverableNamespaces(): array
    {
        return [
            '\\StdClass' => [StdClass::class],
            '\\DateTimeImmutable' => [DateTimeImmutable::class],
        ];
    }

    /**
     * @test
     * @dataProvider classesOutOfDiscoverableNamespaces
     */
    public function returnNullIfClassIsOutOfDiscoverableNamespaces(string $id): void
    {
        $this->decorated
            ->method('get')
            ->with($id)
            ->willReturn(null);

        $null = $this->discoverer->get($id);

        $this->assertNull($null);
    }

    public function classesWithinDiscoverableNamespacesButNotAValidClassName(): array
    {
        return [
            'with \\ in the beginning' => ['Bauhaus\\Doubles\\Invalid'],
            'without \\ in the beginning' => ['\\Bauhaus\\Doubles\\Invalid'],
        ];
    }

    /**
     * @test
     * @dataProvider classesWithinDiscoverableNamespacesButNotAValidClassName
     */
    public function throwExceptionIfIdIsWithinDiscoverableNamespacesButNotAValidClassName(string $id): void
    {
        $this->decorated
            ->method('get')
            ->with($id)
            ->willReturn(null);

        $this->expectException(DefinitionCouldNotBeDiscovered::class);
        $this->expectExceptionMessage(
            'Cannot discover definition if: id is not a valid class name'
        );

        $this->discoverer->get($id);
    }

    /**
     * @test
     */
    public function discoverDefinitionOfServiceWithoutDependencies(): void
    {
        $this->decorated
            ->method('get')
            ->with(ServiceWithoutDependency::class)
            ->willReturn(null);

        $definition = $this->discoverer->get(ServiceWithoutDependency::class);

        $this->assertEquals(
            new ServiceWithoutDependency(),
            $definition->evaluate($this->psrContainer)
        );
    }

    /**
     * @test
     */
    public function discoverDefinitionOfServiceWithDependencies(): void
    {
        $this->decorated
            ->method('get')
            ->with(ServiceWithOneDependency::class)
            ->willReturn(null);
        $this->psrContainer
            ->method('get')
            ->with(ServiceWithoutDependency::class)
            ->willReturn(new ServiceWithoutDependency());

        $definition = $this->discoverer->get(ServiceWithOneDependency::class);

        $this->assertEquals(
            new ServiceWithOneDependency(new ServiceWithoutDependency()),
            $definition->evaluate($this->psrContainer)
        );
    }

    public function servicesWithNonClassDependencies(): array
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
     * @dataProvider servicesWithNonClassDependencies
     */
    public function throwExceptionIfThereIsANonClassDependency(string $id): void
    {
        $this->decorated
            ->method('get')
            ->with($id)
            ->willReturn(null);

        $this->expectException(DefinitionCouldNotBeDiscovered::class);
        $this->expectExceptionMessage(
            'Cannot discover definition if: any of the service dependencies is not a valid class name'
        );

        $this->discoverer->get($id);
    }
}
