<?php

namespace Bauhaus;

use PHPUnit\Framework\TestCase;

class ServiceResolverOptionsTest extends TestCase
{
    /**
     * @test
     */
    public function withDefinitionFilesReturnsANewInstanceWithAddedData(): void
    {
        $initialOptions = ServiceResolverOptions::empty();

        $newOptions = $initialOptions->withDefinitionFiles('file1.php', 'file2.php');

        $this->assertNotSame($initialOptions, $newOptions);
        $this->assertEmpty($initialOptions->definitionFiles());
        $this->assertEquals(
            ['file1.php', 'file2.php'],
            $newOptions->definitionFiles(),
        );
    }

    /**
     * @test
     */
    public function withDiscoverableNamespacesReturnsANewInstanceWithAddedData(): void
    {
        $initialOptions = ServiceResolverOptions::empty();

        $newOptions = $initialOptions->withDiscoverableNamespaces('Foo\\Bar');

        $this->assertNotSame($initialOptions, $newOptions);
        $this->assertEmpty($initialOptions->discoverableNamespaces());
        $this->assertEquals(
            ['Foo\\Bar'],
            $newOptions->discoverableNamespaces(),
        );
    }

    /**
     * @test
     */
    public function discoverableIsEnabledAfterAddingDiscoverableNamespaces(): void
    {
        $initialOptions = ServiceResolverOptions::empty();

        $newOptions = $initialOptions->withDiscoverableNamespaces('Foo\\Bar');

        $this->assertFalse($initialOptions->isDiscoverableEnabled());
        $this->assertTrue($newOptions->isDiscoverableEnabled());
    }
}
