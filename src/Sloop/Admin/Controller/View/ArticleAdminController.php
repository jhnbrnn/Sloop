<?php

namespace Sloop\Admin\Controller\View;

use Psr\Container\ContainerInterface;
use Sloop\Service\ArticleService;

class ArticleAdminController extends AbstractAdminViewController
{
    /**
     * @var ArticleService $articleService
     */
    protected $articleService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->articleService = $container->get('ArticleService');
    }

    public function __invoke($request, $response, $args)
    {
        $flash_article = $this->container->get('flash')->getFirstMessage('article');

        $id = array_shift($args);
        $articles = $this->articleService->getAll();
        if ($id !== null) {
            $article = $this->articleService->getArticle($id);
        } elseif (isset($flash_article)) {
            $article = $flash_article;
        } else {
            $article = null;
        }
        return $this->container->get('view')->render($response, 'admin/admin-article.html.twig', array(
            'token' => $_SESSION['token'],
            'stylesheets' => $this->getStyles(),
            'type' => 'article',
            'articles' => $articles,
            'article' => $article
        ));
    }

    public function route($args)
    {

    }

}