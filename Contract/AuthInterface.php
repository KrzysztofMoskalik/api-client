<?php

namespace KrzysztofMoskalik\ApiClient\Contract;

use Psr\Http\Message\RequestInterface;

interface AuthInterface
{
    /**
     * @psalm-api
     */
    public function authorize(RequestInterface $request): void;

    /**
     * @psalm-api
     */
    public static function supports(): string;
}
