<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin\Controller\App;


use Pimple\Container;
use Sloop\Service\ArticleService;

class ArticleFormController extends AbstractAppController
{

    /**
     * @var ArticleService
     */
    protected $articleService;

    public function __construct(Container $c)
    {
        parent::__construct($c);
        $this->articleService = $c['ArticleService'];
    }

    public function route($args)
    {
        parent::route($args);
        $result = $this->articleService->createArticle($this->request->post());
        if ($result === true) {
            $this->app->redirect("/admin/article");
        } else {
            $this->app->flash('error', 'Cannot create article');
            $this->app->flash('article', $result);
            $this->app->redirect("/admin/article");
        }

    }

}