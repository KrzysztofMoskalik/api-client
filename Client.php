<?php

namespace KrzysztofMoskalik\ApiClient;

use KrzysztofMoskalik\ApiClient\Configuration\ConfigurationRegistry;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use KrzysztofMoskalik\ApiClient\Contract\AuthInterface;
use KrzysztofMoskalik\ApiClient\Contract\ConfigurationRegistryInterface;
use KrzysztofMoskalik\ApiClient\Contract\RepositoryRegistryInterface;
use KrzysztofMoskalik\ApiClient\Repository\AbstractRepository;
use KrzysztofMoskalik\ApiClient\Repository\RepositoryFactory;
use KrzysztofMoskalik\ApiClient\Repository\RepositoryRegistry;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class Client
{
    /**
     * @psalm-api
     */
    public function __construct(
        readonly array $apiConfigurations = [],
        readonly array $resourceConfigurations = [],
        readonly array $globalConfiguration = [],
        readonly array $repositories = [],
        private readonly ?SerializerInterface            $serializer = null,
        private readonly ?ConfigurationRegistryInterface $configurationRegistry = null,
        private readonly ?RepositoryRegistryInterface    $repositoryRegistry = null,
        private ?RepositoryFactory                       $repositoryFactory = null,
    ) {
        $metadataFactory = new ClassMetadataFactory(
            new AttributeLoader()
        );

        if (!$this->repositoryFactory) {
            $this->repositoryFactory = new RepositoryFactory(
                $this->serializer ?? new Serializer(
                    [
                        new ObjectNormalizer($metadataFactory),
                        new ArrayDenormalizer(),
                    ],
                    [
                        new JsonEncoder(),
                    ]
                ),
                $this->configurationRegistry ?? new ConfigurationRegistry(
                    $apiConfigurations,
                    $resourceConfigurations,
                    GlobalConfiguration::fromArray($globalConfiguration)
                ),
                $this->repositoryRegistry ?? new RepositoryRegistry($repositories),
            );
        }
    }

    public function getRepository(string $modelClass): AbstractRepository
    {
        return $this->repositoryFactory->getRepository($modelClass);
    }
}
