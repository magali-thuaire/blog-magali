<?php

namespace App\Controller;

use App\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AppController
{
    public function __construct()
    {
        $this->renderer =  App::getInstance()->getRenderer();
    }

    protected function getManager($entity)
    {
        return App::getInstance()->getManager($entity);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render($view, $variables = [])
    {
        return $this->renderer->render($view, $variables);
    }
}
