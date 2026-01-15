<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

use KrzysztofMoskalik\ApiClient\Contract\AuthInterface;

class ApiConfiguration
{
    public function __construct(
        public string $name {
            get => $this->name;
            set => $this->name = $value;
        },
        public string $baseUrl {
            get => $this->baseUrl;
            set => $this->baseUrl = $value;
        },
        public ?AuthInterface $auth {
            get => $this->auth;
            set => $this->auth = $value;
        },
        public array $extraHeaders = [] {
            get => $this->extraHeaders;
            set => $this->extraHeaders = $value;
        }
    ) {}

    public function supported(): string
    {
        return $this->name;
    }

    public static function fromArray(array $configuration): ApiConfiguration
    {
        return new self(
            $configuration['name'] ?? '',
            $configuration['baseUrl'] ?? '',
            $configuration['auth'],
            $configuration['extraHeaders'] ?? []
        );
    }
}
