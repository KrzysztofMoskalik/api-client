<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

use KrzysztofMoskalik\ApiClient\Contract\ConfigurationRegistryInterface;

class ConfigurationRegistry implements ConfigurationRegistryInterface
{
    public function __construct(
        private array $apiConfigurations = [],
        private array $resourceConfigurations = []
    ) {}

    public function addApiConfiguration(ApiConfiguration $apiConfiguration): void
    {
        $this->apiConfigurations[] = $apiConfiguration;
    }

    public function addResourceConfiguration(ResourceConfiguration $resourceConfiguration): void
    {
        $this->resourceConfigurations[] = $resourceConfiguration;
    }

    public function getApiConfiguration(string $apiName): ?ApiConfiguration
    {
        return array_find(
            $this->apiConfigurations,
            fn($apiConfiguration) => $apiConfiguration->name === $apiName
        );
    }

    public function getResourceConfiguration(string $resourceName): ?ResourceConfiguration
    {
        return array_find(
            $this->resourceConfigurations,
            fn($resourceConfiguration) => $resourceConfiguration->name === $resourceName
        );
    }

    public function getResourceConfigurationForModel(string $modelClass): ?ResourceConfiguration
    {
        return array_find(
            $this->resourceConfigurations,
            fn($resourceConfiguration) => $resourceConfiguration->model === $modelClass
        );
    }
}
