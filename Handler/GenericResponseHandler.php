<?php

namespace KrzysztofMoskalik\ApiClient\Handler;

use KrzysztofMoskalik\ApiClient\Configuration\Endpoint;
use KrzysztofMoskalik\ApiClient\Configuration\EndpointType;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use KrzysztofMoskalik\ApiClient\Configuration\Resource;
use KrzysztofMoskalik\ApiClient\Contract\ResponseHandlerInterface;
use KrzysztofMoskalik\ApiClient\Exception\MissingDataException;
use Psr\Http\Message\ResponseInterface;

class GenericResponseHandler implements ResponseHandlerInterface
{
    public function handleResponse(
        Endpoint $endpoint,
        Resource $resource,
        ResponseInterface $response,
        GlobalConfiguration $configuration
    ): mixed {
        $json = $response->getBody()->getContents();
        $decoded = json_decode($json, true);

        if (empty($decoded)) {
            return null;
        }

        if (!empty($endpoint->getResponseDataPath())) {
            if (!isset($decoded[$endpoint->getResponseDataPath()])) {
                throw new MissingDataException("Response data path not found in response body.");
            }
            $decoded = $decoded[$endpoint->getResponseDataPath()]; //@todo nested paths
        }

        $modelClass = $endpoint->getType() === EndpointType::LIST ? $resource->getModel() . '[]' : $resource->getModel();

        //@todo deserialize nested objects
        return $configuration->getSerializer()->denormalize($decoded, $modelClass);
    }
}