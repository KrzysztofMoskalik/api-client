<?php

namespace KrzysztofMoskalik\ApiClient\Auth;

use KrzysztofMoskalik\ApiClient\Contract\AuthInterface;

/**
 * @psalm-api
 */
class TokenAuth implements AuthInterface
{
    public function __construct(
        public array $configuration = [] {
            set { $this->configuration = $value; }
        }
    ) {}

    #[\Override]
    public function authorize(array &$options): void
    {
        $options['headers'][$this->configuration['header']] = $this->configuration['token'];
    }

    #[\Override]
    public static function supports(): string
    {
        return 'token';
    }
}
