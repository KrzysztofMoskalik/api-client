<?php

namespace KrzysztofMoskalik\ApiClient\Contract;

interface AuthInterface
{
    public array $configuration { set; }

    /**
     * @psalm-api
     */
    public function authorize(array &$options): void;

    /**
     * @psalm-api
     */
    public static function supports(): string;
}
