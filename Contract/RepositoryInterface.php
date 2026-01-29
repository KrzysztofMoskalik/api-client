<?php

namespace KrzysztofMoskalik\ApiClient\Contract;

use KrzysztofMoskalik\ApiClient\Configuration\EndpointType;

interface RepositoryInterface
{
    public static function supports(): string;

    public function get(string $id, array $options = [], EndpointType $endpointType = EndpointType::GET): mixed;

    public function list(array $options = [], EndpointType $endpointType = EndpointType::LIST): mixed;

    public function create(
        array|object $data,
        array $options = [],
        EndpointType $endpointType = EndpointType::CREATE
    ): mixed;

    public function delete(string $id, array $options = [], EndpointType $endpointType = EndpointType::DELETE): mixed;

    public function patch(
        string $id,
        array|object $data,
        array $options = [],
        EndpointType $endpointType = EndpointType::PATCH
    ): mixed;

    public function update(
        string $id,
        array|object $data,
        array $options = [],
        EndpointType $endpointType = EndpointType::UPDATE
    ): mixed;
}
