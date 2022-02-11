<?php

namespace App\Controller;

use App\App;
use Core\Controller\CoreController;

class AppController extends CoreController
{
    protected $template = TEMPLATE;
    protected $viewPath = VIEWS;

    protected function getManager($entity)
    {
        return App::getInstance()->getManager($entity);
    }
}
