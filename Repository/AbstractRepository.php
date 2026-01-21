<?php

namespace KrzysztofMoskalik\ApiClient\Repository;

use KrzysztofMoskalik\ApiClient\Configuration\ApiConfiguration;
use KrzysztofMoskalik\ApiClient\Configuration\Endpoint;
use KrzysztofMoskalik\ApiClient\Configuration\ResourceConfiguration;
use KrzysztofMoskalik\ApiClient\Contract\ConfigurationRegistryInterface;
use KrzysztofMoskalik\ApiClient\Contract\RepositoryInterface;
use KrzysztofMoskalik\ApiClient\Exception\ConfigurationException;
use Override;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @psalm-api
 */
abstract class AbstractRepository implements RepositoryInterface
{
    private string $apiUrl;
    private string $resourcePath;
    private ResourceConfiguration $resourceConfiguration;
    private ApiConfiguration $apiConfiguration;
    private ClientInterface $client;

    /**
     * @psalm-api
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ConfigurationRegistryInterface $configurationRegistry,
        private readonly string $modelClass = '',
    ) {
        $modelClass = $this->getModelClass();

        if (empty($modelClass)) {
            return;
        }

        $this->resourceConfiguration = $this->configurationRegistry->getResourceConfigurationForModel($modelClass);
        $this->apiConfiguration = $this->configurationRegistry->getApiConfiguration($this->resourceConfiguration->api);

        $this->apiUrl = $this->apiConfiguration->baseUrl;
        $this->resourcePath = $this->resourceConfiguration->path;

        $this->client = $this->configurationRegistry->getGlobalConfiguration()->getHttpClient();
    }

    #[Override]
    public static function supports(): string
    {
        return '';
    }

    public function get(string $id, string $endpoint = 'get', array $options = []): mixed
    {
        $endpointConfig = $this->getEndpointConfig($endpoint);
        $this->addExtraHeaders($options);

        $this->authorize($options);

        $response = $this->client->get($this->apiUrl . $this->resourcePath . $id, $options);

        return $this->parseResponse($response, $endpointConfig);
    }

    public function getAll(string $endpoint = 'getAll', array $options = []): mixed
    {
        $endpointConfig = $this->getEndpointConfig($endpoint);
        $this->addExtraHeaders($options);

        $this->authorize($options);

        $response = $this->client->get($this->apiUrl . $this->resourcePath, $options);

        return $this->parseResponse($response, $endpointConfig);
    }

    public function create(object $model, string $endpoint = 'create', array $options = []): mixed
    {
        $endpointConfig = $this->getEndpointConfig($endpoint);
        $this->addExtraHeaders($options);

        $this->authorize($options);

        if ($endpointConfig->requestDataPath) {
            $data = [$endpointConfig->requestDataPath => $model];
        } else {
            $data = $model;
        }

        $options['body'] = $this->serializer->serialize($data, 'json');

        $response = $this->client->post($this->apiUrl . $this->resourcePath, $options);

        return $this->parseResponse($response, $endpointConfig);
    }

    public function delete(string $_id, string $_endpoint = 'delete', array $_options = []): mixed
    {
        /** @todo not implemented yet */
        return null;
    }

    public function patch(string $_id, array $_data, string $_endpoint = 'updatePartial', array $_options = []): mixed
    {
        /** @todo not implemented yet */
        return null;
    }

    public function put(string $_id, object $_model, string $_endpoint = 'update', array $_options = []): mixed
    {
        /** @todo not implemented yet */
        return null;
    }

    protected function authorize(array &$options): void
    {
        $auth = $this->apiConfiguration->auth;

        if (!$auth) {
            return;
        }

        $auth->authorize($options);
    }

    protected function addExtraHeaders(array &$options): void
    {
        if (!empty($this->apiConfiguration->extraHeaders)) {
            $options['headers']
                = array_merge(
                    $options['headers'] ?? [],
                    $this->apiConfiguration->extraHeaders
                );
        }
    }

    protected function getEndpointConfig(string $name): Endpoint
    {
        $endpoint = $this->resourceConfiguration->getEndpoint($name);
        if (!isset($endpoint)) {
            throw new ConfigurationException(
                "Endpoint " . $name . " not configured for resource " . $this->resourceConfiguration->name
            );
        }

        return $endpoint;
    }

    private function parseResponse(ResponseInterface $response, Endpoint $endpointConfig): mixed
    {
        $json = $response->getBody()->getContents();

        $decoded = json_decode($json, true);

        if (!empty($endpointConfig->responseDataPath)) {
            $decoded = $decoded[$endpointConfig->responseDataPath];
        }

        if ($endpointConfig->type === 'getAll') {
            $modelClass = $this->getModelClass() . '[]';
        } else {
            $modelClass = $this->getModelClass();
        }

        return $this->serializer->denormalize($decoded, $modelClass);
    }

    private function getModelClass(): string
    {
        return !empty($this->modelClass) ? $this->modelClass : static::supports();
    }
}
