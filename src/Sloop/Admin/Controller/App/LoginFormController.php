<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin\Controller\App;


use Psr\Container\ContainerInterface;
use Sloop\User\UserService;

class LoginFormController extends AbstractAppController
{

    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->userService = $container->get('UserService');
    }

    public function __invoke($request, $response, $args)
    {
        $params = (array)$request->getParsedBody();
        if ($params['token'] !== $_SESSION['token']) {
            // TODO this doesn't work
            $response->withRedirect($request->getRootUri());
        }
   
        $msg = $this->userService->processLoginForm($params);
        if ($msg === false) {
            $this->container->get('flash')->addMessage('error', 'incorrect username or password');
        }

        return $response
            ->withHeader('Location', '/admin')
            ->withStatus(302);
    }

    public function route($args)
    {
        parent::route($args);
        $msg = $this->userService->processLoginForm($this->request->post());
        if ($msg === false) {
            // $this->app->flash('error', 'incorrect username or password');
        }

        $response->withRedirect("/admin");

    }
}