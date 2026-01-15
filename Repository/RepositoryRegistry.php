<?php

namespace KrzysztofMoskalik\ApiClient\Repository;

use KrzysztofMoskalik\ApiClient\Contract\RepositoryInterface;
use KrzysztofMoskalik\ApiClient\Contract\RepositoryRegistryInterface;
use Override;

class RepositoryRegistry implements RepositoryRegistryInterface
{
    public function __construct(
        private array $repositories = []
    ) {}

    public function add(RepositoryInterface $repository): void
    {
        $this->repositories[] = $repository;
    }

    public function getRepositories(): array
    {
        return $this->repositories;
    }

    #[Override]
    public function getSupported(string $classNamespace): ?RepositoryInterface
    {
        foreach ($this->repositories as $repository) {
            if ($repository::supports($classNamespace) === $classNamespace) {
                return $repository;
            }
        }

        return null;
    }
}
