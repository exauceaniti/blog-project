<?php

namespace Src\Core\Routing;

use Src\Core\Container;
use Src\Core\Middleware\AuthMiddleware;

class Router
{
    private RouteCollection $collection;
    private Container $container;

    public function __construct(string $configPath, ?Container $container = null)
    {
        $this->collection = new RouteCollection(require $configPath);
        $this->container = $container ?? new Container();
        $this->bootBindings(); // Configurer les bindings si besoin
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $match = $this->collection->match($path, $method);

        if (!$match) {
            http_response_code(404);
            $this->call('ErrorController', 'notFound', []);
            return;
        }

        // Middlewares (auth, admin, user)
        foreach ($match['middleware'] as $mw) {
            if ($mw === 'auth') { AuthMiddleware::requireAuth(); }
            if ($mw === 'admin') { AuthMiddleware::requireAdmin(); }
            if ($mw === 'user') { AuthMiddleware::requireUser(); }
        }

        $this->call($match['controller'], $match['method'], $match['params']);
    }

    private function call(string $controllerName, string $method, array $params): void
    {
        $controllerClass = str_starts_with($controllerName, 'Src\\')
            ? $controllerName
            : "Src\\Controller\\{$controllerName}";

        // Instanciation via container (autowiring)
        $controller = $this->container->get($controllerClass);

        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Méthode {$method} introuvable dans {$controllerClass}");
        }

        call_user_func_array([$controller, $method], $params);
    }

    private function bootBindings(): void
    {
        // Exemple: binder PDO/Database/config si tes services en ont besoin
        // $this->container->set(\PDO::class, new \PDO($dsn, $user, $pass));
        // $this->container->bind(\Src\Service\PostService::class, fn($c) => new \Src\Service\PostService($c->get(\Src\DAO\PostDAO::class)));
    }
}
