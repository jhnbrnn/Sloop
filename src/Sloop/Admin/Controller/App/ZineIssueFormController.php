<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin\Controller\App;

use Psr\Container\ContainerInterface;
use Sloop\Service\ZineService;

class ZineIssueFormController extends AbstractAppController
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
        $params = (array)$request->getParsedBody();

        $id = $params['zineId'];
        $success = $this->zineService->createZineIssue($id, $params);
        if ($success) {
            return $response->withHeader('Location', "/admin/zine/" . $id)->withStatus(302);
        } else {
            return $response->withHeader('Location', "/admin/zine")->withStatus(302);
        }
    }

    public function route($args)
    {
        parent::route($args);
        $id = $this->request->post('zineId');
        $success = $this->zineService->createZineIssue($id,
            $this->request->post());
        if ($success) {
            $this->app->redirect("/admin/zine/" . $id);
        }
    }

}