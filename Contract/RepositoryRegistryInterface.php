<?php

namespace KrzysztofMoskalik\ApiClient\Contract;

interface RepositoryRegistryInterface
{
    public function add(RepositoryInterface $repository): void;

    public function getRepositories(): array;

    public function getSupported(string $classNamespace): ?RepositoryInterface;
}
