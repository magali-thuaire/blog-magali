<?php

namespace Core\Controller;

class CoreController
{
    protected string $viewPath;
    protected string $template;

    public function render($view, $variables = [])
    {
        ob_start();
        extract($variables);
        require_once($this->viewPath . '/' . str_replace('.', '/', $view) . '.php');
        $content = ob_get_clean();
        require_once($this->viewPath . '/' . $this->template . '.php');
    }
}
