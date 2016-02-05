<?php
namespace Sloop\Admin\Controller\App;


use Pimple\Container;
use Sloop\Controller\AbstractController;

class AbstractAppController extends AbstractController
{

    protected $request;

    public function __construct(Container $c)
    {
        parent::__construct($c);
        $this->request = $this->app->request();
    }

    public function route($args)
    {
        if ($this->request->post('token') !== $_SESSION['token']) {
            $this->app->redirect($this->request->getRootUri());
        }
    }

}