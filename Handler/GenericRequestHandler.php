<?php

namespace KrzysztofMoskalik\ApiClient\Handler;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use KrzysztofMoskalik\ApiClient\Configuration\Api;
use KrzysztofMoskalik\ApiClient\Configuration\Endpoint;
use KrzysztofMoskalik\ApiClient\Configuration\GlobalConfiguration;
use KrzysztofMoskalik\ApiClient\Contract\RequestHandlerInterface;
use KrzysztofMoskalik\ApiClient\Exception\MissingDataException;
use Psr\Http\Message\RequestInterface;

class GenericRequestHandler implements RequestHandlerInterface
{
    public function prepareRequest(
        Api $api,
        Endpoint $endpoint,
        GlobalConfiguration $configuration,
        array $uriParts = [],
        array $options = []
    ): RequestInterface {
        array_walk($uriParts, fn(&$part) => $part = trim($part, '/'));
        $uri = new Uri(implode('/', $uriParts));

        if ($endpoint->getType()->hasBody()) {
            $options['body'] = $this->prepareBody($endpoint, $configuration, $options['body'] ?? []);
        }

        $this->prepareHeaders($api, $endpoint, $options);
        if ($configuration->shouldAddJsonHeaders()) {
            $this->addJsonHeaders($endpoint, $options);
        }

        $request = new Request(
            $endpoint->getType()->getMethod(),
            $uri,
            $options['headers'] ?? [],
            $options['body'] ?? null
        );

        return $request;
    }

    private function prepareBody(Endpoint $endpoint, GlobalConfiguration $configuration, array|object $body): mixed
    {
        if (empty($body)) {
            return null;
        }

        if (!$endpoint->getRequestDataPath()) {
            return $configuration
                ->getSerializer()
                ->serialize($body, 'json');
        }

        if (is_array($body)) {
            if (empty($body[$endpoint->getRequestDataPath()])) {
                throw  new MissingDataException('Request data path not found in request body.');
            }

            return $configuration
                ->getSerializer()
                ->serialize(
                    $body[$endpoint->getRequestDataPath()],
                    'json'
                );
        }

        if (is_object($body)) {
            if (!isset($body->{$endpoint->getRequestDataPath()})) {
                throw  new MissingDataException('Request data path not found in request body.');
            }
            return $configuration
                ->getSerializer()
                ->serialize(
                    $body->{$endpoint->getRequestDataPath()},
                    'json'
                );
        }
    }

    private function prepareHeaders(Api $api, Endpoint $endpoint, array &$options = []): void
    {
        foreach ($api->getExtraHeaders() as $header => $value) {
            $options['headers'][strtolower($header)] = strtolower($value);
        }

        foreach ($endpoint->getExtraHeaders() as $header => $value) {
            $options['headers'][strtolower($header)] = strtolower($value);
        }
    }

    private function addJsonHeaders(Endpoint $endpoint, array &$options = [])
    {
        if (!isset($options['headers']['accept'])) {
            $options['headers']['accept'] = 'application/json';
        }

        if (!isset($options['headers']['content-type']) && $endpoint->getType()->hasBody()) {
            $options['headers']['content-type'] = 'application/json';
        }
    }
}