<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin\Controller\App;

use Pimple\Container;
use Sloop\Service\ZineService;

class ZineIssueFormController extends AbstractAppController
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