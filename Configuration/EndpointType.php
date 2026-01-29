<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

enum EndpointType: string
{
    const array WITH_BODY = [
        self::CREATE,
        self::UPDATE,
        self::PATCH
    ];

    case GET = 'get';
    case LIST = 'list';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case PATCH = 'patch';

    public function getMethod():string {
        return match($this) {
            self::GET, self::LIST => 'GET',
            self::CREATE => 'POST',
            self::UPDATE => 'PUT',
            self::DELETE => 'DELETE',
            self::PATCH => 'PATCH',
        };
    }

    public function hasBody(): bool
    {
        return in_array($this, self::WITH_BODY);
    }
}