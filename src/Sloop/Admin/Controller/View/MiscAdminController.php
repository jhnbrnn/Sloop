<?php

namespace Sloop\Admin\Controller\View;


class MiscAdminController extends AbstractAdminViewController
{

    public function __invoke($request, $response, $args)
    {
        if (isset($_SESSION['user'])) {
            return $response->withHeader('Location', '/admin')->withStatus(302);
        } else {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    }

    protected function route($args)
    {
    }

}