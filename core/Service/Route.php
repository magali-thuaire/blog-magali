<?php

namespace Core\Service;

class Route
{
    private string $path;
    private array $rights = [];
    private array $get = [];
    private array $post = [];
    private string $controller;
    private array $methodParam = [];

    public function __construct(string $path, string $rights, string $controller, ?array $get, ?array $post, ?array $config)
    {
        $this->path = $path;
        $this->controller = $controller;

        if ($rights !== 'ALL') {
            $this->rights = array_map('trim', explode(',', $config[$rights]));
        }

        if ($get) {
            $this->get['method'] = $get[0] ?? null;
            $this->get['param'] = $get[1] ?? null;
            if (!$this->get['param']) {
                $this->get['default'] = $this->get['method'];
            } else {
                $this->get['default'] = $get[2] ?? null;
            }
        }
        if ($post) {
            $this->post['method'] = $post[0] ?? null;
            $this->post['param'] = $post[1] ?? null;
            if (!$this->post['param']) {
                $this->post['default'] = $this->post['method'];
            } else {
                $this->post['default'] = $post[2] ?? null;
            }
        }
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function get($key)
    {
        return $this->get[$key] ?? null;
    }

    public function post($key)
    {
        return $this->post[$key] ?? null;
    }

    public function verifGetParam(Request $request): bool
    {
        if ($params = $this->get('param')) {
            foreach ($params as $param) {
                if (!$request->get('query', $param)) {
                    return false;
                } else {
                    $tab[] = $request->get('query', $param);
                }
            }
            $this->methodParam = $tab;
            return true;
        }

        return false;
    }

    public function verifPostParam(Request $request): bool
    {
        if ($params = $this->post('param')) {
            foreach ($params as $param) {
                if (!$request->get('query', $param)) {
                    return false;
                } else {
                    $tab[] = $request->get('query', $param);
                }
            }
            $this->methodParam = $tab;
            return true;
        }

        return false;
    }

    public function getMethodParam(): array
    {
        return $this->methodParam;
    }

    public function getRights(): array
    {
        return $this->rights;
    }
}
