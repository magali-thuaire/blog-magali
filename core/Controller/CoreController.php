<?php

namespace Core\Controller;

use Core\Model\FormModel;

class CoreController
{

	protected $viewPath;
	protected $template;

	public function render($view, $form = null)
	{
		ob_start();
		require_once ($this->viewPath . '/' . str_replace('.', '/', $view) . '.php');
		$content = ob_get_clean();
		require_once($this->viewPath . '/' . $this->template . '.php');
	}
}