<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

use KrzysztofMoskalik\ApiClient\Contract\RepositoryInterface;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GlobalConfiguration
{
    public function __construct(
        private ?ClientInterface $httpClient = null,
        private ?SerializerInterface $serializer = null,
        private array $repositories = [],
        private bool $addJsonHeaders = true
    ) {}

    public function getHttpClient(): ?ClientInterface
    {
        return $this->httpClient;
    }

    public function setHttpClient(?ClientInterface $httpClient): GlobalConfiguration
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function getSerializer(): ?SerializerInterface
    {
        return $this->serializer;
    }

    public function setSerializer(?SerializerInterface $serializer): GlobalConfiguration
    {
        $this->serializer = $serializer;

        return $this;
    }

    public function addRepository(RepositoryInterface $repository): void
    {
        $this->repositories[] = $repository;
    }

    public function getRepositories(): array
    {
        return $this->repositories;
    }

    public function shouldAddJsonHeaders(): bool
    {
        return $this->addJsonHeaders;
    }

    public function addJsonHeaders(bool $addJsonHeaders = true): void
    {
        $this->addJsonHeaders = $addJsonHeaders;
    }
}