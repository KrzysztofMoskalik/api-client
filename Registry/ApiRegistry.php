<?php

namespace KrzysztofMoskalik\ApiClient\Registry;

use KrzysztofMoskalik\ApiClient\Configuration\Api;
use KrzysztofMoskalik\ApiClient\Exception\ConfigurationException;

class ApiRegistry
{
    private array $apis = [];

    public function __construct(
        array $apis = []
    ) {
        foreach ($apis as $api) {
            $this->addApi($api);
        }
    }

    public function addApi(Api $api): void
    {
        $this->apis[$api->getName()] = $api;
    }

    public function getApi(string $name): Api
    {
        if (!isset($this->apis[$name])) {
            throw new ConfigurationException("Api with name '$name' does not exist.");
        }
        return $this->apis[$name];
    }

    public function getForModel(string $modelClass): Api
    {
        foreach ($this->apis as $api) {
            foreach ($api->getResources() as $resource) {
                if ($resource->getModel() === $modelClass) {
                    return $api;
                }
            }
        }

        throw new ConfigurationException("Api for model '$modelClass' does not exist.");
    }
}