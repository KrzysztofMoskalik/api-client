<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

use KrzysztofMoskalik\ApiClient\Contract\AuthInterface;
use KrzysztofMoskalik\ApiClient\Exception\ConfigurationException;
use KrzysztofMoskalik\ApiClient\Handler\EmptyResponseHandler;
use KrzysztofMoskalik\ApiClient\Registry\ResourceRegistry;

class Api
{
    private ResourceRegistry $resourceRegistry;

    public function __construct(
        private string $name,
        private ?string $baseUrl = null,
        private ?AuthInterface $auth = null,
        private array $resources = [],
        private array $extraHeaders = [],
    )
    {
        $this->resourceRegistry = new ResourceRegistry($this->resources);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(?string $baseUrl): Api
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function getAuth(): ?AuthInterface
    {
        return $this->auth;
    }

    public function setAuth(?AuthInterface $auth): Api
    {
        $this->auth = $auth;

        return $this;
    }

    public function getResource(string $name): Resource
    {
        return $this->resourceRegistry->getResource($name);
    }

    public function getResources(): array
    {
        return $this->resourceRegistry->getResources();
    }

    public function addResource(Resource $resource): Api
    {
        $this->resourceRegistry->addResource($resource);

        return $this;
    }

    public function getResourceForModel(string $modelClass): Resource
    {
        foreach ($this->resourceRegistry->getResources() as $resource) {
            if ($resource->getModel() === $modelClass) {
                return $resource;
            }
        }

        throw new ConfigurationException("Resource for model '$modelClass' does not exist.");
    }

    public function getExtraHeaders(): array
    {
        return $this->extraHeaders;
    }

    public function setExtraHeaders(array $extraHeaders): Api
    {
        $this->extraHeaders = $extraHeaders;

        return $this;
    }

    public function addExtraHeader(string $headerName, string $headerValue): Api
    {
        $this->extraHeaders[$headerName] = $headerValue;

        return $this;
    }

    public function generic(string $modelClass, string $responseDataPath = ''): Api
    {
        $this->addResource(
            new Resource($modelClass)
                ->addEndpoint(new Endpoint('get', responseDataPath: $responseDataPath))
                ->addEndpoint(new Endpoint('list', responseDataPath: $responseDataPath))
                ->addEndpoint(new Endpoint('create', responseDataPath: $responseDataPath))
                ->addEndpoint(new Endpoint('update', responseDataPath: $responseDataPath))
                ->addEndpoint(new Endpoint('patch', responseDataPath: $responseDataPath))
                ->addEndpoint(
                    new Endpoint('delete', responseDataPath: $responseDataPath)
                        ->setResponseHandler(new EmptyResponseHandler())
                )
        );

        return $this;
    }
}