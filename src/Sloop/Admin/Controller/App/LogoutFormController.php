<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin\Controller\App;


use Pimple\Container;
use Sloop\User\UserService;

class LogoutFormController extends AbstractAppController
{

    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(Container $c)
    {
        parent::__construct($c);
        $this->userService = $c['UserService'];
    }

    public function route($args)
    {
        parent::route($args);
        $this->userService->processLogoutForm();
        $this->app->redirect($this->request->getRootUri());
    }

}