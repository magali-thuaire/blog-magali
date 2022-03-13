<?php

namespace Core\Service;

use Core\Entity\CoreUserEntity;

class Router
{
    private ?array $routes;
    private ?CoreUserEntity $user;

    public function __construct(?array $config, ?CoreUserEntity $user)
    {
        foreach ($config['ROUTES'] as $path => $param) {
            $this->routes[$path] =  new Route($path, $param[0], $param[1], $param[2] ?? null, $param[3] ?? null, $config);
        }
        $this->user = $user;
    }

    public function match(string $p, Request $request, $app)
    {
        if (array_key_exists($p, $this->routes)) {
            $route = $this->routes[$p];
            if (!empty($route->getRights()) && (!$this->user || (!in_array($this->user->getRole(), $route->getRights())))) {
                return $app->forbidden();
            }

            $controllerName = $route->getController();
            $controller = new $controllerName();
            if ($request->get('server', 'REQUEST_METHOD') === 'GET') {
                if (!$route->get('param')) {
                    return $controller($route->get('default'));
                } elseif ($route->verifGetParam($request)) {
                    return $controller($route->get('method'), $route->getMethodParam());
                } else {
                    return $controller($route->get('default'));
                }
            } elseif ($request->get('server', 'REQUEST_METHOD') === 'POST') {
                if ($route->verifPostParam($request)) {
                    return $controller($route->post('method'), $route->getMethodParam());
                }
                return $controller($route->post('method'));
            }
        } else {
            $app->notFound();
        }
    }
}
