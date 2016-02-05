<?php

namespace Sloop\Admin\Controller\View;


use Pimple\Container;
use Sloop\Service\ZineService;

class ZineIssueAdminController extends AbstractAdminViewController
{

    /**
     * @var ZineService
     */
    protected $zineService;

    public function __construct(Container $c)
    {
        parent::__construct($c);
        $this->zineService = $c['ZineService'];
    }

    protected function route($args)
    {
        $id = array_shift($args);
        $issueId = array_shift($args);
        $issue = $this->zineService->getZineIssue($id, $issueId);

        $this->app->render('admin/admin-zine-issue.html.twig', array(
            'token' => $_SESSION['token'],
            'stylesheets' => $this->getStyles(),
            'type' => 'zine-issue',
            'zineId' => $id,
            'issue' => $issue
        ));
    }

}