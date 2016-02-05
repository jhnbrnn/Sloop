<?php

namespace Sloop\Admin\Controller\View;


use Sloop\Controller\AbstractController;

abstract class AbstractAdminViewController extends AbstractController
{
    protected function getStyles($key = null)
    {
        return [
            'array' => [
                'name' => 'admin.css',
                'media' => 'screen, projection'
            ]
        ];
    }

    protected function getScripts($key = null)
    {
        return [];
    }
}