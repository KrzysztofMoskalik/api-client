<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

use Psr\Http\Client\ClientInterface;

readonly class GlobalConfiguration
{
    public function __construct(
        private ClientInterface $client
    ) {}

    public static function fromArray(array $globalConfiguration): GlobalConfiguration
    {
        $client = null;
        if (!empty($globalConfiguration['client'])) {
            if (is_object($globalConfiguration['client'])) {
                $client = $globalConfiguration['client'];
            } else {
                $client = new $globalConfiguration['client']();
            }
        }

        return new self($client);
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->client;
    }
}