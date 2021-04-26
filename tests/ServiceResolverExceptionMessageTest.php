<?php

namespace Bauhaus;

use Bauhaus\Doubles\DiscoverNamespaceA\CircularDependencyA;
use Bauhaus\Doubles\DiscoverNamespaceA\CircularDependencyB;
use Bauhaus\Doubles\DiscoverNamespaceA\CircularDependencyC;
use Bauhaus\Doubles\DiscoverNamespaceA\CircularDependencyD;
use Bauhaus\Doubles\DiscoverNamespaceA\NotFoundMessage1;
use Bauhaus\Doubles\DiscoverNamespaceA\NotFoundMessage2;
use Bauhaus\Doubles\DiscoverNamespaceA\NotFoundMessage3;
use Bauhaus\Doubles\DiscoverNamespaceA\ServiceThatThrowsException;
use Bauhaus\Doubles\DiscoverNamespaceB\ServiceWithScalarIntDependency;
use Bauhaus\Doubles\DiscoverNamespaceB\SubdependencyOnScalar1;
use Bauhaus\Doubles\DiscoverNamespaceB\SubdependencyOnScalar2;
use Bauhaus\Doubles\UndiscoverableService;
use PHPUnit\Framework\TestCase;

class ServiceResolverExceptionMessageTest extends TestCase
{
    use DoubleDefinitionTrait;

    private ServiceResolver $resolver;

    protected function setUp(): void
    {
        $options = ServiceResolverOptions::empty()
            ->withDefinitionFiles(
                $this->definitionPath('definitions-file-1.php'),
                $this->definitionPath('definitions-file-2.php'),
            )
            ->withDiscoverableNamespaces(
                'Bauhaus\\Doubles\\DiscoverNamespaceA',
                'Bauhaus\\Doubles\\DiscoverNamespaceB',
            );

        $this->resolver = ServiceResolver::build($options);
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
                Service not found
                    requested -> $notFoundDependency
                MSG,
            ],
            'two levels stack' => [
                $service1,
                <<<MSG
                Service not found
                    requested -> $service1
                     V
                    dependency not resolved -> $notFoundDependency
                MSG,
            ],
            'three levels stack' => [
                $service2,
                <<<MSG
                Service not found
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
                Service not found
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
    public function notFoundExceptionMessageHasCallTrace(string $id, string $expected): void
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
            Service not found
                requested -> $calledServiceId
                 V
                dependency resolved -> $dependencyId
                 V
                dependency not resolved -> $serviceWithErrorId
                 > Cannot discover definition if: any of the service dependencies is not a valid class name
            MSG);

        $this->resolver->get($calledServiceId);
    }

    public function serviceIdWithExpectedDefinitionErrorMessage(): array
    {
        $d = CircularDependencyD::class;
        $c = CircularDependencyC::class;
        $b = CircularDependencyB::class;
        $a = CircularDependencyA::class;

        return [
            'one level stack' => [
                $a,
                <<<MSG
                Error while evaluating service
                    requested -> $a
                     V
                    dependency resolved -> $b
                     V
                    dependency not resolved -> $a
                     > Circular dependency detected
                MSG,
            ],
            'two levels stack' => [
                $b,
                <<<MSG
                Error while evaluating service
                    requested -> $b
                     V
                    dependency resolved -> $a
                     V
                    dependency not resolved -> $b
                     > Circular dependency detected
                MSG,
            ],
            'three levels stack' => [
                $c,
                <<<MSG
                Error while evaluating service
                    requested -> $c
                     V
                    dependency resolved -> $b
                     V
                    dependency resolved -> $a
                     V
                    dependency not resolved -> $b
                     > Circular dependency detected
                MSG,
            ],
            'four levels stack' => [
                $d,
                <<<MSG
                Error while evaluating service
                    requested -> $d
                     V
                    dependency resolved -> $c
                     V
                    dependency resolved -> $b
                     V
                    dependency resolved -> $a
                     V
                    dependency not resolved -> $b
                     > Circular dependency detected
                MSG,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider serviceIdWithExpectedDefinitionErrorMessage
     */
    public function errorExceptionMessageHasCallTrace(string $id, string $expected): void
    {
        $this->expectExceptionMessage($expected);

        $this->resolver->get($id);
    }

    /**
     * @test
     */
    public function unexpectedErrorMessageIsShown(): void
    {
        $id = ServiceThatThrowsException::class;

        $this->expectExceptionMessage(<<<MSG
            Error while evaluating service
                requested -> $id
                 > Error occurred
            MSG);

        $this->resolver->get(ServiceThatThrowsException::class);
    }
}
