<?php

namespace Sloop\Admin\Controller\View;


use Psr\Container\ContainerInterface;
use Sloop\Service\ZineService;

class ZineIssueAdminController extends AbstractAdminViewController
{

    /**
     * @var ZineService
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
        $issueId = array_shift($args);
        $issue = $this->zineService->getZineIssue($id, $issueId);

        return $this->container->get('view')->render($response, 'admin/admin-zine-issue.html.twig', array(
            'token' => $_SESSION['token'],
            'stylesheets' => $this->getStyles(),
            'type' => 'zine-issue',
            'zineId' => $id,
            'issue' => $issue
        ));
    }

    public function route($args) {

    }

}