<?php

namespace Bauhaus;

use Bauhaus\ServiceResolver\Resolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ServiceResolverOptionsTest extends TestCase
{
    private ServiceResolver $resolver;
    private Resolver|MockObject $resolverChain;

    protected function setUp(): void
    {
        $this->resolverChain = $this->createMock(Resolver::class);
        $this->resolver = new ServiceResolver($this->resolverChain);
    }

    /**
     * @test
     */
    public function withDefinitionFilesReturnsANewInstanceWithAddedData(): void
    {
        $initialOptions = ServiceResolverOptions::create();

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
        $initialOptions = ServiceResolverOptions::create();

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
        $initialOptions = ServiceResolverOptions::create();

        $newOptions = $initialOptions->withDiscoverableNamespaces('Foo\\Bar');

        $this->assertFalse($initialOptions->isDiscoverableEnabled());
        $this->assertTrue($newOptions->isDiscoverableEnabled());
    }
}
