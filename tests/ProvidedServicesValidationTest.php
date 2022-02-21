<?php

namespace Bauhaus;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProvidedServicesValidationTest extends TestCase
{
    /**
     * @test
     */
    public function throwExceptionIfDefinitionFileDoesNotExist(): void
    {
        $settings = ServiceResolverSettings::new()
            ->withDefinitionFiles('invalid-file-path');

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Definition file does not exist: invalid-file-path');

        ServiceResolver::build($settings);
    }

    /**
     * @test
     */
    public function throwExceptionIfDefinitionFileDoesNotReturnArray(): void
    {
        $filePath = __DIR__ . '/Doubles/definitions-file-invalid-returning.php';
        $settings = ServiceResolverSettings::new()
            ->withDefinitionFiles($filePath);

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage("Definition file must return array: {$filePath}");

        ServiceResolver::build($settings);
    }

    /**
     * @test
     */
    public function throwExceptionIfDefinitionFromFileIsInvalid(): void
    {
        $settings = ServiceResolverSettings::new()
            ->withDefinitionFiles(__DIR__ . '/Doubles/definitions-file-invalid-definition.php');

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage("Invalid service provided");

        ServiceResolver::build($settings);
    }
}
