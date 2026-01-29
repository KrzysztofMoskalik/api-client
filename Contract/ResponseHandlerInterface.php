<?php

namespace KrzysztofMoskalik\ApiClient\Contract;

use KrzysztofMoskalik\ApiClient\Configuration\Endpoint;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use KrzysztofMoskalik\ApiClient\Configuration\Resource;
use Psr\Http\Message\ResponseInterface;

interface ResponseHandlerInterface
{
    public function handleResponse(
        Endpoint $endpoint,
        Resource $resource,
        ResponseInterface $response,
        GlobalConfiguration $configuration
    ): mixed;
}