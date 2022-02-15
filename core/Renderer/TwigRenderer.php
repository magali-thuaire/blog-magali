<?php

namespace Core\Renderer;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

class TwigRenderer
{
    protected string $viewPath;
    private Environment $twig;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;

        $loader = new FilesystemLoader($this->viewPath);
        $this->twig = new Environment($loader, ['debug' => true]);
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new IntlExtension());
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function render($view, $variables = [])
    {
        echo $this->twig->render($view, $variables);
    }
}
