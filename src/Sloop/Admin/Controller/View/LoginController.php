<?php

namespace Sloop\Admin\Controller\View;

class LoginController extends AbstractAdminViewController
{
    public function __invoke($request, $response, $args)
    {
        if (isset($_SESSION['user'])) {
            error_log('logged in!');
            return $response
                ->withHeader('Location', 'admin/article')
                ->withStatus(302);
        }

        $flash = $this->container->get('flash');
        
        return $this->container->get('view')->render($response, 'admin/login.html.twig', array(
            'stylesheets' => $this->getStyles(),
            'scripts' => $this->getScripts(),
            'html_title' => '',
            'is_front' => false,
            'logged_in' => false,
            'token' => $_SESSION['token'],
            'flash' => $flash->getMessages()
        ));
    }

    protected function route($args)
    {
        
    }
}