<?php

namespace KrzysztofMoskalik\ApiClient\Repository;

use KrzysztofMoskalik\ApiClient\Configuration\Api;
use KrzysztofMoskalik\ApiClient\Configuration\Endpoint;
use KrzysztofMoskalik\ApiClient\Configuration\EndpointType;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use KrzysztofMoskalik\ApiClient\Configuration\Resource;
use KrzysztofMoskalik\ApiClient\Contract\RepositoryInterface;
use Override;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    public function __construct(
        private Api $api,
        private Resource $resource,
        private GlobalConfiguration $configuration,
    ) {}

    #[Override]
    public static function supports(): string
    {
        return '';
    }

    public function get(string $id, array $options = [], EndpointType $endpointType = EndpointType::GET): mixed
    {
        return $this->doAction(
            $endpointType,
            [$this->api->getBaseUrl(), $this->resource->getPath(), $id],
            $options
        );
    }

    public function list(array $options = [], EndpointType $endpointType = EndpointType::LIST): mixed
    {
        return $this->doAction(
            $endpointType,
            [$this->api->getBaseUrl(), $this->resource->getPath()],
            $options
        );
    }

    public function create(array|object $data, array $options = [], EndpointType $endpointType = EndpointType::CREATE): mixed
    {
        $options['body'] = $data;

        return $this->doAction(
            $endpointType,
            [$this->api->getBaseUrl(), $this->resource->getPath()],
            $options
        );
    }

    public function delete(string $id, array $options = [], EndpointType $endpointType = EndpointType::DELETE): mixed
    {
        return $this->doAction(
            $endpointType,
            [$this->api->getBaseUrl(), $this->resource->getPath(), $id],
            $options
        );
    }

    public function patch(
        string $id,
        array|object $data,
        array $options = [],
        EndpointType $endpointType = EndpointType::PATCH
    ): mixed {
        $options['body'] = $data;

        return $this->doAction(
            $endpointType,
            [$this->api->getBaseUrl(), $this->resource->getPath(), $id],
            $options
        );
    }

    public function update(
        string $id,
        array|object $data,
        array $options = [],
        EndpointType $endpointType = EndpointType::UPDATE
    ): mixed {
        $options['body'] = $data;

        return $this->doAction(
            $endpointType,
            [$this->api->getBaseUrl(), $this->resource->getPath(), $id],
            $options
        );
    }

    private function prepareRequest(Endpoint $endpoint, array $uriParts = [], array $options = []): RequestInterface
    {
        $request = $endpoint->getRequestHandler()->prepareRequest($this->api, $endpoint, $this->configuration, $uriParts, $options);

        $auth = $this->api->getAuth();

        if ($auth) {
            $auth->authorize($request);
        }

        return $request;
    }

    private function handleResponse(Endpoint $endpoint, ResponseInterface $response): mixed
    {
        $handler = $endpoint->getResponseHandler();
        return $handler->handleResponse($endpoint, $this->resource, $response, $this->configuration);
    }

    private function doAction(EndpointType $endpointType, array $uriParts = [], array $options = [])
    {
        $endpoint = $this->resource->getEndpointByType($endpointType);

        $request = $this->prepareRequest(
            $endpoint,
            $uriParts,
            $options
        );

        $client = $this->configuration->getHttpClient();
        $response = $client->sendRequest($request);

        return $this->handleResponse($endpoint, $response);
    }
}
