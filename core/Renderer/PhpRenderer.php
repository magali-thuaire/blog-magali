<?php

namespace Core\Renderer;

class PhpRenderer
{
    protected string $viewPath;
    protected string $template;

    public function __construct(string $viewPath, string $template)
    {
        $this->viewPath = $viewPath;
        $this->template = $template;
    }

    public function render($view, $variables = [])
    {
        ob_start();
        extract($variables);
        require_once($this->viewPath . '/' . str_replace('.', '/', $view) . '.php');
        $content = ob_get_clean();
        require_once($this->viewPath . '/' . $this->template . '.php');
    }
}
