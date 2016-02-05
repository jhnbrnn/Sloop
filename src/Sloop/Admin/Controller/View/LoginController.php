<?php

namespace Sloop\Admin\Controller\View;

class LoginController extends AbstractAdminViewController
{
    protected function route($args)
    {
        if (isset($_SESSION['user'])) {
            $this->app->redirect('admin/article');
        }

        $this->app->render('admin/login.html.twig', array(
            'stylesheets' => $this->getStyles(),
            'scripts' => $this->getScripts(),
            'html_title' => '',
            'is_front' => false,
            'logged_in' => false,
            'token' => $_SESSION['token']
        ));
    }
}