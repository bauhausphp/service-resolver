<?php

namespace Bauhaus\ServiceResolver\Factory;

use Bauhaus\DoubleDefinitionTrait;
use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use Bauhaus\ServiceResolver\ActualDefinition;
use PHPUnit\Framework\TestCase;

class DefinitionLoaderTest extends TestCase
{
    use DoubleDefinitionTrait;

    private DefinitionLoader $loader;

    protected function setUp(): void
    {
        $this->loader = new DefinitionLoader();
    }

    /**
     * @test
     */
    public function returnServiceDefinitionFromFile(): void
    {
        $expected = [
            ServiceWithoutDependency::class => ActualDefinition::create(fn () => 'who cares'),
            ServiceWithOneDependency::class => ActualDefinition::create(fn () => 'who cares'),
            'service-alias' => ActualDefinition::create(fn () => 'who cares?'),
            'without-callback' => ActualDefinition::create(fn () => 'who cares?'),
        ];

        $serviceDefinitions = $this->loader->loadFromFiles(
            $this->definitionPath('definitions-file-1.php'),
            $this->definitionPath('definitions-file-2.php'),
        );

        $this->assertEquals($expected, $serviceDefinitions);
    }

    /**
     * @test
     */
    public function throwExceptionIfFilePathDoesNotExist(): void
    {
        $this->expectException(DefinitionLoaderException::class);
        $this->expectExceptionMessage('Files not found: unexistent-1, unexistent-2');

        $this->loader->loadFromFiles('unexistent-1', 'unexistent-2');
    }

    /**
     * @test
     */
    public function throwExceptionIfFileDoesNotReturnArray(): void
    {
        $invalidFile1 = $this->definitionPath('definitions-file-invalid-returning.php');
        $invalidFile2 = $this->definitionPath('definitions-file-invalid-returning.php');
        $validFile1 = $this->definitionPath('definitions-file-1.php');
        $validFile2 = $this->definitionPath('definitions-file-2.php');

        $this->expectException(DefinitionLoaderException::class);
        $this->expectExceptionMessage("Files must return array: $invalidFile1, $invalidFile2");

        $this->loader->loadFromFiles($invalidFile1, $validFile1, $invalidFile2, $validFile2);
    }

    /**
     * @test
     */
    public function throwExceptionIfFilesReturnInvalidServiceDefinition(): void
    {
        $invalidFile = $this->definitionPath('definitions-file-invalid-definition.php');

        $this->expectException(DefinitionLoaderException::class);
        $this->expectExceptionMessage("Invalid definitions: invalid-definition");

        $this->loader->loadFromFiles($invalidFile);
    }
}
