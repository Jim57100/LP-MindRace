<?php

namespace Framework\Routing;

class Route
{
    public function __construct(
        protected array|string $method,
        protected string $uri,
        protected $handler,
    ) {
        $this->setMethod($method);
    }

    public function getMethod(): array
    {
        return $this->method;
    }

    public function setMethod(string|array $method): Route
    {
        $this->method = (array)$method;

        return $this;
    }

    public function getUri(): string
    {

        return $this->uri;
    }

    public function setUri(string $uri): Route
    {
        $this->uri = $uri;

        return $this;
    }

    public function getHandler()
    {

        return $this->handler;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }
}
