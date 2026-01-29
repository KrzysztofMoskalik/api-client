<?php

namespace KrzysztofMoskalik\ApiClient\Auth;

use KrzysztofMoskalik\ApiClient\Contract\AuthInterface;
use Psr\Http\Message\RequestInterface;

/**
 * @psalm-api
 */
class BearerAuth implements AuthInterface
{
    public function __construct(
        public array $configuration = [] {
            set { $this->configuration = $value; }
        }
    ) {}

    #[\Override]
    public function authorize(RequestInterface $request): void
    {
        /** @todo not implemented yet */
    }

    #[\Override]
    public static function supports(): string
    {
        return 'bearer';
    }
}
