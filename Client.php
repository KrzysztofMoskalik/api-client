<?php

namespace KrzysztofMoskalik\ApiClient;

use KrzysztofMoskalik\ApiClient\Configuration\Api;
use KrzysztofMoskalik\ApiClient\Configuration\ClientConfiguration;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use KrzysztofMoskalik\ApiClient\Contract\RepositoryInterface;
use KrzysztofMoskalik\ApiClient\Registry\ApiRegistry;
use KrzysztofMoskalik\ApiClient\Repository\RepositoryFactory;

//@todo create generic minimal-config crud client
class Client
{
    public GlobalConfiguration $globals;
    private ApiRegistry $apiRegistry;
    private RepositoryFactory $repositoryFactory;

    public function __construct(
        private ?ClientConfiguration $configuration = null,
        array $apis = []
    )
    {
        if ($configuration === null) {
            $this->configuration = new ClientConfiguration();
        }

        $this->globals = $this->configuration->getGlobalConfiguration();
        $this->apiRegistry = new ApiRegistry($apis);

        $this->repositoryFactory = new RepositoryFactory(
            $this->configuration,
            $this->apiRegistry
        );
    }

    public function getConfiguration(): ClientConfiguration
    {
        return $this->configuration;
    }

    public function setConfiguration(ClientConfiguration $configuration): Client
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function addApi(Api $api): Client
    {
        $this->apiRegistry->addApi($api);

        return $this;
    }

    public function getRepository(string $modelClass): RepositoryInterface
    {
        return $this->repositoryFactory->getRepository($modelClass);
    }
}