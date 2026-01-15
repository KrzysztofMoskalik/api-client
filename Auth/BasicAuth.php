<?php

namespace KrzysztofMoskalik\ApiClient\Auth;

use KrzysztofMoskalik\ApiClient\Contract\AuthInterface;

/**
 * @psalm-api
 */
class BasicAuth implements AuthInterface
{
    public function __construct(
        public array $configuration = [] {
            set { $this->configuration = $value; }
        }
    ) {}

    #[\Override]
    public function authorize(array &$options): void
    {
        $credentials = base64_encode($this->configuration['username'] . ':' . $this->configuration['password']);
        $options['headers']['Authorization'] = sprintf(
            'Basic %s',
            $credentials
        );
    }

    #[\Override]
    public static function supports(): string
    {
        return 'basic';
    }
}
