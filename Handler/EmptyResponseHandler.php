<?php

namespace KrzysztofMoskalik\ApiClient\Handler;

use KrzysztofMoskalik\ApiClient\Configuration\Endpoint;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use KrzysztofMoskalik\ApiClient\Configuration\Resource;
use KrzysztofMoskalik\ApiClient\Contract\ResponseHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class EmptyResponseHandler implements ResponseHandlerInterface
{
    public function handleResponse(
        Endpoint $endpoint,
        Resource $resource,
        ResponseInterface $response,
        GlobalConfiguration $configuration
    ): mixed {
        return null;
    }
}