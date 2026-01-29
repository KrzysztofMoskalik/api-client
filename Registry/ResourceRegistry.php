<?php

namespace KrzysztofMoskalik\ApiClient\Registry;

use KrzysztofMoskalik\ApiClient\Configuration\Resource;
use KrzysztofMoskalik\ApiClient\Exception\ConfigurationException;

class ResourceRegistry
{
    private array $resources = [];

    /**
     * @param array $resources
     */
    public function __construct(array $resources = [])
    {
        foreach ($resources as $resource) {
            $this->addResource($resource);
        }
    }

    public function addResource(Resource $resource): ResourceRegistry
    {
        $this->resources[$resource->getName()] = $resource;

        return $this;
    }

    public function getResource(string $name): Resource
    {
        if (!isset($this->resources[$name])) {
            throw new ConfigurationException("Resource with name '$name' does not exist.");
        }

        return $this->resources[$name];
    }

    public function getResources(): array
    {
        return $this->resources;
    }
}