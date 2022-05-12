<?php

namespace Sloop\Controller;

use Psr\Container\ContainerInterface;
use Slim\App;

abstract class AbstractController
{

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * AbstractController constructor.
     * @param $c Container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected abstract function route($args);

    protected function getStyles($key = null)
    {
    }

    protected function getScripts($key = null)
    {
        if ($key) {
            //return
        } else {

        }
    }

    public function getRoute()
    {
        return function () {
            $args = func_get_args();
            return $this->route($args);
        };
    }
}