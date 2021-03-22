<?php

namespace Bauhaus\ServiceResolver;

use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use PHPUnit\Framework\TestCase;

class ServiceDefinitionLoaderTest extends TestCase
{
    private ServiceDefinitionLoader $loader;

    protected function setUp(): void
    {
        $this->loader = new ServiceDefinitionLoader();
    }

    /**
     * @test
     */
    public function returnServiceDefinitionFromFile(): void
    {
        $expected = [
            ServiceWithoutDependency::class => ServiceDefinition::build(fn () => 'who cares'),
            ServiceWithOneDependency::class => ServiceDefinition::build(fn () => 'who cares'),
            'service-alias' => ServiceDefinition::build(fn () => 'who cares?'),
        ];

        $serviceDefinitions = $this->loader->loadFromFiles(
            __DIR__ . '/definitions-file-1.php',
            __DIR__ . '/definitions-file-2.php',
        );

        $this->assertEquals($expected, $serviceDefinitions);
    }

    /**
     * @test
     */
    public function throwExceptionIfFilePathDoesNotExist(): void
    {
        $this->expectException(ServiceDefinitionLoaderException::class);
        $this->expectExceptionMessage('Files not found: unexistent-1, unexistent-2');

        $this->loader->loadFromFiles('unexistent-1', 'unexistent-2');
    }

    /**
     * @test
     */
    public function throwExceptionIfFileDoesNotReturnArray(): void
    {
        $invalidFile1 = __DIR__ . '/definitions-file-invalid-returning.php';
        $invalidFile2 = __DIR__ . '/definitions-file-invalid-returning.php';

        $this->expectException(ServiceDefinitionLoaderException::class);
        $this->expectExceptionMessage("Files must return array: $invalidFile1, $invalidFile2");

        $this->loader->loadFromFiles(
            $invalidFile1,
            __DIR__ . '/definitions-file-1.php',
            $invalidFile2,
            __DIR__ . '/definitions-file-2.php',
        );
    }


    /**
     * @test
     */
    public function throwExceptionIfFilesReturnInvalidServiceDefinition(): void
    {
        $invalidFile = __DIR__ . '/definitions-file-invalid-definition.php';

        $this->expectException(ServiceDefinitionLoaderException::class);
        $this->expectExceptionMessage("Invalid service definitions: invalid-definition");

        $this->loader->loadFromFiles($invalidFile);
    }
}
