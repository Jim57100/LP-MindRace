<?php

namespace Framework;

use Framework\Routing\Routing;

final class Kernel
{
    private Routing $routing;

    public function __construct()
    {
        $this->routing = new Routing();
    }

    public function handle(string $method, string $uri)
    {
        [
            'handler' => $handler,
            'vars' => $args,
        ] = $this->routing->findHandlerForUriAndMethod($method, $uri);

        if (!is_callable($handler) && class_exists($handler)) {
            $handler = new $handler();
        }

        if (is_callable($handler)) {
            echo $handler(...$args);
        } else {
            throw new \LogicException('Le handler n\'est pas un callable !');
        }
    }
}
