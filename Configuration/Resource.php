<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

use KrzysztofMoskalik\ApiClient\Exception\ConfigurationException;
use ReflectionClass;

class Resource
{
    private string $path;
    private array $endpoints = [];
    private string $modelClass;

    public function __construct(
        private string $name,
        ?string $path = null,
        ?string $modelClass = null,
        array $endpoints = []
    ) {
        if (class_exists($name)) {
            $modelClass = $name;
            $this->name = strtolower((new ReflectionClass($this->name))->getShortName());
            $this->setPath('/' . $this->name . 's');
        } else {
            $this->name = strtolower($name);
            $this->setPath('/' . $this->name);
        }

        if (!empty($modelClass)) {
            $this->setModel($modelClass);
        }

        if (!empty($path)) {
            $this->setPath($path);
        }

        foreach ($endpoints as $endpoint) {
            $this->addEndpoint($endpoint);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPath(string $path): Resource
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getModel(): string
    {
        return $this->modelClass;
    }

    public function setModel(string $modelClass): Resource
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    public function addEndpoint(Endpoint $endpoint): Resource
    {
        $this->endpoints[$endpoint->getName()] = $endpoint;

        return $this;
    }

    public function getEndpoint(string $name): Endpoint
    {
        if (!isset($this->endpoints[$name])) {
            throw new ConfigurationException('Endpoint ' . $name . ' not found for resource ' . $this->name . '.');
        }

        return $this->endpoints[$name];
    }

    public function getEndpointByType(EndpointType $type): Endpoint
    {
        foreach ($this->endpoints as $endpoint) {
            if ($endpoint->getType() === $type) {
                return $endpoint;
            }
        }

        throw new ConfigurationException('Endpoint of type ' . $type->name . ' not found for resource ' . $this->name . '.');
    }
}