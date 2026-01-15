<?php

namespace KrzysztofMoskalik\ApiClient\Repository;

use KrzysztofMoskalik\ApiClient\Contract\ConfigurationRegistryInterface;
use KrzysztofMoskalik\ApiClient\Contract\RepositoryRegistryInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @psalm-api
 */
readonly class RepositoryFactory
{
    public function __construct(
        private SerializerInterface            $serializer,
        private ConfigurationRegistryInterface $configurationRegistry,
        private RepositoryRegistryInterface    $repositoryRegistry
    ) {}

    public function getRepository(string $modelClass): AbstractRepository
    {
        $repository = $this->repositoryRegistry->getSupported($modelClass);

        if ($repository) {
            return $repository;
        }

        $repository = $this->createRepository($modelClass);

        $this->repositoryRegistry->add($repository);

        return $repository;
    }

    public function createRepository(string $modelClass): AbstractRepository
    {
        return new BaseRepository(
            $this->serializer,
            $this->configurationRegistry,
            $modelClass,
        );
    }
}
