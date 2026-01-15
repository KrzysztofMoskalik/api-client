<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

class Endpoint
{
    public function __construct(
        public string $name {
            get => $this->name;
            set => $this->name = $value;
        },
        public string $requestDataPath {
            get => $this->requestDataPath;
            set => $this->requestDataPath = $value;
        },
        public string $responseDataPath {
            get => $this->responseDataPath;
            set => $this->responseDataPath = $value;
        },
        public string $type {
            get => $this->type;
            set => $this->type = $value;
        }
    ) {}

    public static function fromArray(array $endpoint): Endpoint
    {
        return new self(
            $endpoint['name'] ?? '',
            $endpoint['requestDataPath'] ?? '',
            $endpoint['responseDataPath'] ?? '',
            $endpoint['type'] ?? ''
        );
    }
}
