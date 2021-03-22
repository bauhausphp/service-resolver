<?php

namespace Bauhaus\ServiceResolver;

/**
 * @internal
 */
final class ServiceDefinitionLoader
{
    /**
     * @throws ServiceDefinitionLoaderException
     * @return ServiceDefinition[]
     */
    public function loadFromFiles(string ...$files): array
    {
        $this->assertFilesExist(...$files);
        $filesContent = $this->loadFiles(...$files);

        return $this->buildServiceDefinitions($filesContent);
    }

    private function assertFilesExist(string ...$files): void
    {
        $notFoundFiles = array_filter($files, fn ($f) => !file_exists($f));

        if ([] !== $notFoundFiles) {
            throw ServiceDefinitionLoaderException::filesNotFound(...$notFoundFiles);
        }
    }

    private function loadFiles(string ...$files): array
    {
        $invalidFiles = [];
        $allFilesContent = [];

        foreach ($files as $f) {
            $fileContent = require $f;

            if (false === is_array($fileContent)) {
                $invalidFiles[] = $f;
                continue;
            }

            $allFilesContent = array_merge($allFilesContent, $fileContent);
        }

        if ([] !== $invalidFiles) {
            throw ServiceDefinitionLoaderException::filesDoNotReturnArray(...$invalidFiles);
        }

        return $allFilesContent;
    }

    private function buildServiceDefinitions(array $filesContent): array
    {
        $invalidDefinitions = [];
        $serviceDefinitions = [];

        foreach ($filesContent as $k => $c) {
            try {
                $serviceDefinitions[$k] = ServiceDefinition::build($c);
            } catch (ServiceDefinitionCouldNotBeBuild) {
                $invalidDefinitions[] = $k;
            }
        }

        if ([] !== $invalidDefinitions) {
            throw ServiceDefinitionLoaderException::invalidServiceDefinition(...$invalidDefinitions);
        }

        return $serviceDefinitions;
    }
}
