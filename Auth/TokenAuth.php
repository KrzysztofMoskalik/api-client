<?php

namespace KrzysztofMoskalik\ApiClient\Auth;

use KrzysztofMoskalik\ApiClient\Contract\AuthInterface;
use KrzysztofMoskalik\ApiClient\Exception\ConfigurationException;
use Psr\Http\Message\RequestInterface;

/**
 * @psalm-api
 */
class TokenAuth implements AuthInterface
{
    public function __construct(
        private string $header,
        private string $token
    ) {
        if (empty($this->header) || empty($this->token)) {
            throw new ConfigurationException('Header and token must be set for TokenAuth.');
        }
    }

    #[\Override]
    public function authorize(RequestInterface $request): void
    {
        $request->withAddedHeader($this->header, $this->token);
    }

    #[\Override]
    public static function supports(): string
    {
        return 'token';
    }
}
