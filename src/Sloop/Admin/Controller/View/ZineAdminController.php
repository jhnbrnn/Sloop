<?php

namespace Sloop\Admin\Controller\View;


use Psr\Container\ContainerInterface;
use Sloop\Service\ZineService;

class ZineAdminController extends AbstractAdminViewController
{
    /**
     * @var ZineService $zineService
     */
    protected $zineService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->zineService = $container->get('ZineService');
    }

    public function __invoke($request, $response, $args)
    {
        $id = array_shift($args);   
        $zines = $this->zineService->getAll();
        $zine = $this->zineService->getZine($id);
        if ($zine) {
            $issues = $this->zineService->getIssuesForZine($id);
        } else {
            $issues = [];
        }

        return $this->container->get('view')->render($response, 'admin/admin-zine.html.twig', array(
            'token' => $_SESSION['token'],
            'stylesheets' => $this->getStyles(),
            'type' => 'zine',
            'zines' => $zines,
            'zine' => $zine,
            'issues' => $issues
        ));
    }

    protected function route($args)
    {
    }
}