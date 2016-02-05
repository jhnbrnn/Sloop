<?php

namespace Sloop\Controller;


use Pimple\Container;
use Slim\Slim;

abstract class AbstractController
{

    /**
     * @var Container $container
     */
    protected $container;
    /**
     * @var null|Slim $app
     */
    protected $app;


    /**
     * AbstractController constructor.
     * @param $c Container
     */
    public function __construct($c)
    {
        $this->container = $c;
        $this->app = Slim::getInstance();
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