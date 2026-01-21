<?php

namespace KrzysztofMoskalik\ApiClient\Contract;

use KrzysztofMoskalik\ApiClient\Configuration\ApiConfiguration;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use KrzysztofMoskalik\ApiClient\Configuration\ResourceConfiguration;

interface ConfigurationRegistryInterface
{
    public function addApiConfiguration(ApiConfiguration $apiConfiguration): void;

    public function addResourceConfiguration(ResourceConfiguration $resourceConfiguration): void;

    public function getApiConfiguration(string $apiName): ?ApiConfiguration;

    public function getResourceConfiguration(string $resourceName): ?ResourceConfiguration;

    public function getResourceConfigurationForModel(string $modelClass): ?ResourceConfiguration;

    public function setGlobalConfiguration(GlobalConfiguration $globalConfiguration): void;

    public function getGlobalConfiguration(): GlobalConfiguration;
}
