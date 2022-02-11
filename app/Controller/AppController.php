<?php

namespace App\Controller;

use App\App;
use Core\Controller\CoreController;

class AppController extends CoreController
{
    protected string $template = TEMPLATE;
    protected string $viewPath = VIEWS;

    protected function getManager($entity)
    {
        return App::getInstance()->getManager($entity);
    }
}
