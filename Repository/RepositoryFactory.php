<?php

namespace KrzysztofMoskalik\ApiClient\Repository;

use KrzysztofMoskalik\ApiClient\Configuration\ClientConfiguration;
use KrzysztofMoskalik\ApiClient\Contract\RepositoryInterface;
use KrzysztofMoskalik\ApiClient\Registry\ApiRegistry;
use KrzysztofMoskalik\ApiClient\Registry\RepositoryRegistry;

/**
 * @psalm-api
 */
readonly class RepositoryFactory
{
    private RepositoryRegistry $repositoryRegistry;

    public function __construct(
        private ClientConfiguration $configuration,
        private ApiRegistry $apiRegistry
    ) {
        $this->repositoryRegistry = new RepositoryRegistry(
            $this->configuration
                ->getGlobalConfiguration()
                ->getRepositories()
        );
    }

    public function getRepository(string $modelClass): RepositoryInterface
    {
        $repository = $this->repositoryRegistry->getSupported($modelClass);

        if ($repository) {
            return $repository;
        }

        $repository = $this->createRepository($modelClass);

        $this->repositoryRegistry->add($repository);

        return $repository;
    }

    public function createRepository(string $modelClass): RepositoryInterface
    {
        $api = $this->apiRegistry->getForModel($modelClass);
        $resource = $api->getResourceForModel($modelClass);

        return new BaseRepository(
            $api,
            $resource,
            $this->configuration->getGlobalConfiguration(),
        );
    }
}
