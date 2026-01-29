<?php

namespace KrzysztofMoskalik\ApiClient\Registry;

use KrzysztofMoskalik\ApiClient\Contract\RepositoryInterface;

class RepositoryRegistry
{
    private array $repositories = [];

    public function __construct(
        array $repositories = []
    ) {}

    public function add(RepositoryInterface $repository): void
    {
        $this->repositories[$repository::supports()] = $repository;
    }

    public function getSupported(string $classNamespace): ?RepositoryInterface
    {
        if (!isset($this->repositories[$classNamespace])) {
            return null;
        }

        return $this->repositories[$classNamespace];
    }
}
