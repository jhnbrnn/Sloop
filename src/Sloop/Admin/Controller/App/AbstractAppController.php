<?php
namespace Sloop\Admin\Controller\App;

use Sloop\Controller\AbstractController;

class AbstractAppController extends AbstractController
{
    public function route($args)
    {
        if ($this->request->post('token') !== $_SESSION['token']) {
            $this->app->redirect($this->request->getRootUri());
        }
    }

}