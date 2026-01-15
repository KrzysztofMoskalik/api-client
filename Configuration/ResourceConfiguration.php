<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

class ResourceConfiguration
{
    public function __construct(
        public string $name {
            get => $this->name;
            set => $this->name = $value;
        },
        public string $path {
            get => $this->path;
            set => $this->path = $value;
        },
        public string $model {
            get => $this->model;
            set => $this->model = $value;
        },
        public string $api {
            get => $this->api;
            set => $this->api = $value;
        },
        public array $endpoints = [] {
            get => $this->endpoints;
            set => $this->endpoints = $value;
        }
    ) {}

    public function addEndpoint(Endpoint $endpoint): void
    {
        $this->endpoints[] = $endpoint;
    }

    public function getEndpoint(string $name): ?Endpoint
    {
        return array_find($this->endpoints, fn($endpoint) => $endpoint->name === $name);
    }

    public static function fromArray(array $configuration): ResourceConfiguration
    {
        $endpoints = [];

        foreach ($configuration['endpoints'] ?? [] as $endpointName => $endpoint) {
            $endpoints[] = Endpoint::fromArray(array_merge($endpoint, ['name' => $endpointName]));
        }

        return new self(
            $configuration['name'] ?? '',
            $configuration['path'] ?? '',
            $configuration['model'] ?? '',
            $configuration['api'] ?? '',
            $endpoints
        );
    }
}
