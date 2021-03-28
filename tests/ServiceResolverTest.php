<?php

namespace Bauhaus;

use Bauhaus\Doubles\DiscoverNamespaceA\CircularDependencyA;
use Bauhaus\Doubles\DiscoverNamespaceA\CircularDependencyB;
use Bauhaus\Doubles\DiscoverNamespaceA\CircularDependencyC;
use Bauhaus\Doubles\DiscoverNamespaceA\CircularDependencyD;
use Bauhaus\Doubles\DiscoverNamespaceA\DiscoverableA1;
use Bauhaus\Doubles\DiscoverNamespaceA\DiscoverableA2;
use Bauhaus\Doubles\DiscoverNamespaceA\NotFoundMessage1;
use Bauhaus\Doubles\DiscoverNamespaceA\NotFoundMessage2;
use Bauhaus\Doubles\DiscoverNamespaceA\NotFoundMessage3;
use Bauhaus\Doubles\DiscoverNamespaceA\ServiceWithManyDependencies;
use Bauhaus\Doubles\DiscoverNamespaceB\DiscoverableB;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarArrayDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarBoolDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarIntDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarStringDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithVariadicDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\SubdependencyOnScalar1;
use Bauhaus\Doubles\DiscoverNamespaceB\SubdependencyOnScalar2;
use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use Bauhaus\Doubles\UndiscoverableService;
use Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector\CircularDependencyDetected;
use DateTimeImmutable;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;
use StdClass;

class ServiceResolverTest extends TestCase
{
    use DoubleDefinitionTrait;

    private ServiceResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = ServiceResolverOptions::empty()
            ->withDefinitionFiles(
                $this->definitionPath('definitions-file-1.php'),
                $this->definitionPath('definitions-file-2.php'),
            )
            ->withDiscoverableNamespaces(
                'Bauhaus\\Doubles\\DiscoverNamespaceA',
                'Bauhaus\\Doubles\\DiscoverNamespaceB',
            )
            ->build();
    }

    public function unresolvableServiceIds(): array
    {
        return [
            'discoverable but with array dep' => [ServiceWithScalarArrayDependency::class],
            'discoverable but with bool dep' => [ServiceWithScalarBoolDependency::class],
            'discoverable but with int dep' => [ServiceWithScalarIntDependency::class],
            'discoverable but with string dep' => [ServiceWithScalarStringDependency::class],
            'discoverable but with variadic dep' => [ServiceWithVariadicDependency::class],
            'undiscoverable and not defined #1' => [UndiscoverableService::class],
            'undiscoverable and not defined #2' => [DateTimeImmutable::class],
            'undiscoverable and not defined #3' => [PDO::class],
            'not defined string id' => ['undefined'],
        ];
    }

    /**
     * @test
     * @dataProvider unresolvableServiceIds
     */
    public function doesNotHaveServiceIfItCannotBeResolved(string $id): void
    {
        $result = $this->resolver->has($id);

        $this->assertFalse($result);
    }

    /**
     * @test
     * @dataProvider unresolvableServiceIds
     */
    public function throwExceptionIfTryToGetServiceThatCannotBeResolved(string $id): void
    {
        $this->expectException(PsrNotFoundException::class);

        $this->resolver->get($id);
    }

    public function resolvableServices(): array
    {
        return [
            'discoverable without dep' => [DiscoverableA1::class],
            'discoverable with one dep' => [DiscoverableA2::class],
            'discoverable with two deps' => [DiscoverableB::class],
            'defined without dep' => [ServiceWithoutDependency::class],
            'defined with one dep' => [ServiceWithOneDependency::class],
            'defined with many deps' => [ServiceWithManyDependencies::class],
        ];
    }

    /**
     * @test
     * @dataProvider resolvableServices
     */
    public function hasServiceIfItCanBeResolved(string $id): void
    {
        $result = $this->resolver->has($id);

        $this->assertTrue($result);
    }


    /**
     * @test
     * @dataProvider resolvableServices
     */
    public function returnServiceIfItCanBeResolved(string $id): void
    {
        $result = $this->resolver->get($id);

        $this->assertInstanceOf($id, $result);
    }

    /**
     * @test
     */
    public function hasServiceThatWasDefinedAsAlias(): void
    {
        $result = $this->resolver->has('service-alias');

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function returnServiceThatWasDefinedAsAlias(): void
    {
        $result = $this->resolver->get('service-alias');

        $this->assertInstanceOf(ServiceWithoutDependency::class, $result);
    }


    /**
     * @test
     */
    public function hasServiceThatWasDefinedWithoutACallback(): void
    {
        $result = $this->resolver->has('without-callback');

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function returnServiceThatWasDefinedWithoutACallback(): void
    {
        $result = $this->resolver->get('without-callback');

        $this->assertInstanceOf(StdClass::class, $result);
    }

    public function servicesWithCircularDependency(): array
    {
        return [
            [CircularDependencyA::class],
            [CircularDependencyB::class],
            [CircularDependencyC::class],
            [CircularDependencyD::class],
        ];
    }

    /**
     * @test
     * @dataProvider servicesWithCircularDependency
     */
    public function throwExceptionIfCircularReferenceIsDetected(string $id): void
    {
        $this->expectException(CircularDependencyDetected::class);

        $this->resolver->get($id);
    }

    /**
     * @test
     */
    public function returnTheSameServiceIfItWasAlreadyCalledBefore(): void
    {
        $first = $this->resolver->get(ServiceWithoutDependency::class);
        $second = $this->resolver->get(ServiceWithoutDependency::class);

        $this->assertSame($second, $first);
    }

    public function serviceIdWithExpectedDefinitionNotFoundMessage(): array
    {
        $service3 = NotFoundMessage3::class;
        $service2 = NotFoundMessage2::class;
        $service1 = NotFoundMessage1::class;
        $notFoundDependency = UndiscoverableService::class;

        return [
            'one level stack' => [
                $notFoundDependency,
                <<<MSG
                Definition not found
                    requested -> $notFoundDependency
                MSG,
            ],
            'two levels stack' => [
                $service1,
                <<<MSG
                Definition not found
                    requested -> $service1
                     V
                    dependency not resolved -> $notFoundDependency
                MSG,
            ],
            'three levels stack' => [
                $service2,
                <<<MSG
                Definition not found
                    requested -> $service2
                     V
                    dependency resolved -> $service1
                     V
                    dependency not resolved -> $notFoundDependency
                MSG,
            ],
            'four levels stack' => [
                $service3,
                <<<MSG
                Definition not found
                    requested -> $service3
                     V
                    dependency resolved -> $service2
                     V
                    dependency resolved -> $service1
                     V
                    dependency not resolved -> $notFoundDependency
                MSG,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider serviceIdWithExpectedDefinitionNotFoundMessage
     */
    public function notFoundExceptionMessageHasACallTrace(string $id, string $expected): void
    {
        $this->expectExceptionMessage($expected);

        $this->resolver->get($id);
    }

    /**
     * @test
     */
    public function notFoundExceptionMessageCarriesOriginalExceptionMessage(): void
    {
        $calledServiceId = SubdependencyOnScalar2::class;
        $dependencyId = SubdependencyOnScalar1::class;
        $serviceWithErrorId = ServiceWithScalarIntDependency::class;

        $this->expectExceptionMessage(<<<MSG
            Definition not found
                requested -> $calledServiceId
                 V
                dependency resolved -> $dependencyId
                 V
                dependency not resolved -> $serviceWithErrorId
                 > Cannot discover definition if: any of the service dependencies is not a valid class name
            MSG);

        $this->resolver->get($calledServiceId);
    }
}
