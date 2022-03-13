<?php

namespace Core\Service;

use Core\Security\Security;

class Request
{
    private $request;

    public function __construct()
    {
        $this->request['query'] = (isset($_GET) && !empty($_GET)) ? Security::checkInputs($_GET) : null;
        $this->request['request'] = (isset($_POST) && !empty($_POST)) ? Security::checkInputs($_POST) : null;
        $this->request['session'] = (isset($_SESSION) && !empty($_SESSION)) ? $_SESSION : null;
        $this->request['server'] = (isset($_SERVER) && !empty($_SERVER)) ? $_SERVER : null;
    }

    public function get($param, $key)
    {
        return ($this->request[$param][$key]) ?? null;
    }

    public function all($param): ?array
    {
        return ($this->request[$param]) ?? null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        $this->request['session'][$key] = $_SESSION[$key];
    }

    public function unset($key)
    {
        unset($_SESSION[$key]);
        unset($this->request['session'][$key]);
    }
}
