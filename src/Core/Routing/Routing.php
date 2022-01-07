<?php

namespace Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Routing
{

    private Dispatcher $dispatcher;

    //récupère un tableau de routes
    public function __construct()
    {
        $routes = require __DIR__ . '/../../../config/routing.php';
        $this->dispatcher = simpleDispatcher(function(RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                /** @var Route $route */
                $r->addRoute($route->getMethod(), $route->getUri(), $route->getHandler());
            }
        });
    }

    public function findHandlerForUriAndMethod(string $method, string $uri)
    {
        $routeInfo = $this->dispatcher->dispatch($method, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                echo 'Route non trouvée : ';
                echo $uri;
                die;
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                echo 'Route trouvée mais méthode non autorisée !';die;
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                return [
                    'handler' => $handler,
                    'vars' => $vars,
                ];
        }
    }
}
