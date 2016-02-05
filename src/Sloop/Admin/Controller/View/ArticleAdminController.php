<?php

namespace Sloop\Admin\Controller\View;

use Pimple\Container;
use Sloop\Service\ArticleService;

class ArticleAdminController extends AbstractAdminViewController
{
    /**
     * @var ArticleService $articleService
     */
    protected $articleService;

    public function __construct(Container $c)
    {
        parent::__construct($c);
        $this->articleService = $c['ArticleService'];
    }

    public function route($args)
    {
        $id = array_shift($args);
        $articles = $this->articleService->getAll();
        if ($id !== null) {
            $article = $this->articleService->getArticle($id);
        } elseif (isset($this->app->flashData()['article'])) {
            $article = $this->app->flashData()['article'];
        } else {
            $article = null;
        }
        $this->app->render('admin/admin-article.html.twig', array(
            'token' => $_SESSION['token'],
            'stylesheets' => $this->getStyles(),
            'type' => 'article',
            'articles' => $articles,
            'article' => $article
        ));
    }

}