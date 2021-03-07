<?php

namespace Bauhaus;

use Bauhaus\Doubles\DiscoverServiceA;
use Bauhaus\Doubles\DiscoverServiceB;
use Bauhaus\Doubles\DiscoverServiceC;
use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;
use StdClass;

class RealServiceResolverWithoutDiscovererTest extends TestCase
{
    private ServiceResolver $resolver;

    protected function setUp(): void
    {
        $options = ServiceResolverOptions::create()
            ->withDefinitionFiles(
                __DIR__.'/ServiceResolver/definitions-file-1.php',
                __DIR__.'/ServiceResolver/definitions-file-2.php',
            );

        $this->resolver = ServiceResolverFactory::build($options);
    }

    public function idsWithExpectedHasResults(): array
    {
        return [
            'undefined #1' => ['undefined', false],
            'undefined #2' => [StdClass::class, false],
            'defined and without dep' => [ServiceWithoutDependency::class, true],
            'defined and with dep' => [ServiceWithOneDependency::class, true],
            'using service alias' => ['service-alias', true],
        ];
    }

    /**
     * @test
     * @dataProvider idsWithExpectedHasResults
     */
    public function hasServiceIfItWasDefined(string $id, bool $expected): void
    {
        $result = $this->resolver->has($id);

        $this->assertEquals($expected, $result);
    }

    public function notDefinedServices(): array
    {
        return [
            'undefined string' => ['undefined'],
            'discover service a' => [DiscoverServiceA::class],
            'discover service b' => [DiscoverServiceB::class],
            'discover service c' => [DiscoverServiceC::class],
        ];
    }

    /**
     * @test
     * @dataProvider notDefinedServices
     */
    public function throwExceptionIfServiceIsNotDefined(string $id): void
    {
        $this->expectException(PsrNotFoundException::class);

        $this->resolver->get($id);
    }

    public function definedServices(): array
    {
        return [
            'defined and without dep' => [ServiceWithoutDependency::class],
            'defined and with dep' => [ServiceWithOneDependency::class],
        ];
    }

    /**
     * @test
     * @dataProvider definedServices
     */
    public function returnInstanceOfServiceIfDefined(string $id): void
    {
        $service = $this->resolver->get($id);

        $this->assertInstanceOf($id, $service);
    }

    /**
     * @test
     */
    public function returnInstanceOfServiceIfDefinedUsingAlias(): void
    {
        $service = $this->resolver->get('service-alias');

        $this->assertInstanceOf(ServiceWithoutDependency::class, $service);
    }
}
