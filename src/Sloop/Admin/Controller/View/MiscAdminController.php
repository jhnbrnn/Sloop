<?php

namespace Sloop\Admin\Controller\View;


class MiscAdminController extends AbstractAdminViewController
{

    protected function route($args)
    {
        if (!isset($_SESSION['user'])) {
            $root = $this->app->request()->getRootUri();
            $this->app->redirect($root . '/admin');
        }
        $this->app->pass();
    }

}