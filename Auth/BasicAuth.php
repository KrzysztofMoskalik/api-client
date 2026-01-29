<?php

namespace KrzysztofMoskalik\ApiClient\Auth;

use KrzysztofMoskalik\ApiClient\Contract\AuthInterface;
use KrzysztofMoskalik\ApiClient\Exception\ConfigurationException;
use Psr\Http\Message\RequestInterface;

/**
 * @psalm-api
 */
readonly class BasicAuth implements AuthInterface
{
    public function __construct(
        private string $username,
        private string $password,
    ) {
        if (empty($this->username) || empty($this->password)) {
            throw new ConfigurationException('Username and password must be set for BasicAuth.');
        }
    }

    #[\Override]
    public function authorize(RequestInterface $request): void
    {
        $credentials = base64_encode($this->username . ':' . $this->password);
        $request->withAddedHeader('Authorization', sprintf('Basic %s', $credentials));
    }

    #[\Override]
    public static function supports(): string
    {
        return 'basic';
    }
}
