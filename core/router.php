<?php

declare(strict_types=1);

namespace Proyecto\core;

use App\Core\Exceptions\HttpException;

class Router
{
    private array $routes = [];

    public function __construct(
        private Request $request,
        private Response $response,
        private Container $container
    ) {}

    public function get(
        string $uri,
        array $action
    ): void {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(
        string $uri,
        array $action
    ): void {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute(
        string $method,
        string $uri,
        array $action
    ): void {
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch(): void
    {
        $method = $this->request->method();

        $uri = $this->request->uri();

        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            throw new HttpException(
                'Route not found',
                404
            );
        }

        [$controller, $controllerMethod] = $action;

        $instance = $this->container->get($controller);

        $instance->$controllerMethod(
            $this->request,
            $this->response
        );
    }
}