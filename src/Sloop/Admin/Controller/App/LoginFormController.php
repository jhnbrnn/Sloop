<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin\Controller\App;


use Pimple\Container;
use Sloop\User\UserService;

class LoginFormController extends AbstractAppController
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
        $msg = $this->userService->processLoginForm($this->request->post());
        if ($msg === false) {
            $this->app->flash('error', 'incorrect username or password');
        }

        $this->app->redirect("/admin");

    }
}