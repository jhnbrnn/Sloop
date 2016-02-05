<?php

namespace Sloop\Admin\Controller\View;


use Pimple\Container;
use Sloop\Service\ZineService;

class ZineAdminController extends AbstractAdminViewController
{

    /**
     * @var ZineService $zineService
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

        $zines = $this->zineService->getAll();
        $zine = $this->zineService->getZine($id);
        if ($zine) {
            $issues = $this->zineService->getIssuesForZine($id);
        } else {
            $issues = [];
        }

        $this->app->render('admin/admin-zine.html.twig', array(
            'token' => $_SESSION['token'],
            'stylesheets' => $this->getStyles(),
            'type' => 'zine',
            'zines' => $zines,
            'zine' => $zine,
            'issues' => $issues
        ));
    }

}