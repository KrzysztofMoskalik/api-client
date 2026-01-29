<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

use KrzysztofMoskalik\ApiClient\Contract\RequestHandlerInterface;
use KrzysztofMoskalik\ApiClient\Contract\ResponseHandlerInterface;
use KrzysztofMoskalik\ApiClient\Handler\GenericRequestHandler;
use KrzysztofMoskalik\ApiClient\Handler\GenericResponseHandler;

class Endpoint
{
    public function __construct(
        private string $name,
        private string $requestDataPath = '',
        private string $responseDataPath = '',
        private EndpointType $type = EndpointType::GET,
        private ?RequestHandlerInterface $requestHandler = null,
        private ?ResponseHandlerInterface $responseHandler = null,
        private array $extraHeaders = []
    ) {
        foreach (EndpointType::cases() as $endpointType) {
            if ($endpointType->value === $this->name) {
                $this->setType($endpointType);
            }
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequestDataPath(): ?string
    {
        return $this->requestDataPath;
    }

    public function setRequestDataPath(?string $requestDataPath): Endpoint
    {
        $this->requestDataPath = $requestDataPath;

        return $this;
    }

    public function getResponseDataPath(): ?string
    {
        return $this->responseDataPath;
    }

    public function setResponseDataPath(?string $responseDataPath): Endpoint
    {
        $this->responseDataPath = $responseDataPath;

        return $this;
    }

    public function getType(): EndpointType
    {
        return $this->type;
    }

    public function setType(EndpointType $type): Endpoint
    {
        $this->type = $type;

        return $this;
    }

    public function getRequestHandler(): RequestHandlerInterface
    {
        if (!$this->requestHandler) {
            return new GenericRequestHandler();
        }

        return $this->requestHandler;
    }

    public function setRequestHandler(RequestHandlerInterface $requestHandler): Endpoint
    {
        $this->requestHandler = $requestHandler;

        return $this;
    }

    public function getResponseHandler(): ResponseHandlerInterface
    {
        if (!$this->responseHandler) {
            return new GenericResponseHandler();
        }

        return $this->responseHandler;
    }

    public function setResponseHandler(ResponseHandlerInterface $responseHandler): Endpoint
    {
        $this->responseHandler = $responseHandler;

        return $this;
    }

    public function getExtraHeaders(): array
    {
        return $this->extraHeaders;
    }

    public function setExtraHeaders(array $extraHeaders): Endpoint
    {
        $this->extraHeaders = $extraHeaders;

        return $this;
    }

    public function addExtraHeader(string $headerName, string $headerValue): Endpoint
    {
        $this->extraHeaders[$headerName] = $headerValue;

        return $this;
    }
}
