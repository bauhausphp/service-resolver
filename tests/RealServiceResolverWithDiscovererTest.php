<?php

namespace Bauhaus;

use Bauhaus\Doubles\DiscoverServiceA;
use Bauhaus\Doubles\DiscoverServiceB;
use Bauhaus\Doubles\ServiceWithScalarArrayDependency;
use Bauhaus\Doubles\ServiceWithScalarBoolDependency;
use Bauhaus\Doubles\ServiceWithScalarIntDependency;
use Bauhaus\Doubles\ServiceWithScalarStringDependency;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;

class RealServiceResolverWithDiscovererTest extends TestCase
{
    private ServiceResolver $resolver;

    protected function setUp(): void
    {
        $options = ServiceResolverOptions::create()
            ->withDiscoverableNamespaces('\\Bauhaus\\Doubles\\');

        $this->resolver = ServiceResolverFactory::build($options);
    }

    public function notDiscoverableServices(): array
    {
        return [
            [ServiceWithScalarArrayDependency::class],
            [ServiceWithScalarBoolDependency::class],
            [ServiceWithScalarIntDependency::class],
            [ServiceWithScalarStringDependency::class],
        ];
    }

    /**
     * @test
     * @dataProvider notDiscoverableServices
     */
    public function doesNotServiceIfItCannotBeDiscovered(string $id): void
    {
        $result = $this->resolver->has($id);

        $this->assertFalse($result);
    }

    /**
     * @test
     * @dataProvider notDiscoverableServices
     */
    public function throwExceptionIfTryToGetServiceThatCannotBeDiscovered(string $id): void
    {
        $this->expectException(PsrNotFoundException::class);

        $this->resolver->get($id);
    }

    public function discoverableServices(): array
    {
        return [
            [DiscoverServiceA::class],
            [DiscoverServiceB::class],
            [DiscoverServiceB::class],
        ];
    }

    /**
     * @test
     * @dataProvider discoverableServices
     */
    public function hasServiceIfItCanBeDiscovered(string $id): void
    {
        $result = $this->resolver->has($id);

        $this->assertTrue($result);
    }

    /**
     * @test
     * @dataProvider discoverableServices
     */
    public function returnServiceIfItCanBeDiscovered(string $id): void
    {
        $result = $this->resolver->get($id);

        $this->assertInstanceOf($id, $result);
    }
}
