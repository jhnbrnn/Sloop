<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin\Controller\App;


use Psr\Container\ContainerInterface;
use Sloop\User\UserService;

class LogoutFormController extends AbstractAppController
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
        $this->userService->processLogoutForm();
        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    public function route($args)
    {
    }

}