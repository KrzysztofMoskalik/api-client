<?php

namespace KrzysztofMoskalik\ApiClient\Contract;

use KrzysztofMoskalik\ApiClient\Configuration\Api;
use KrzysztofMoskalik\ApiClient\Configuration\Endpoint;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use Psr\Http\Message\RequestInterface;

interface RequestHandlerInterface
{
    public function prepareRequest(
        Api $api,
        Endpoint $endpoint,
        GlobalConfiguration $configuration,
        array $uriParts = [],
        array $options = [],
    ): RequestInterface;
}