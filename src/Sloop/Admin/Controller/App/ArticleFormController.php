<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin\Controller\App;


use Psr\Container\ContainerInterface;
use Sloop\Service\ArticleService;

class ArticleFormController extends AbstractAppController
{

    /**
     * @var ArticleService
     */
    protected $articleService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->articleService = $container->get('ArticleService');
    }

    public function __invoke($request, $response, $args)
    {
        // todo check if user is logged in

        $params = (array)$request->getParsedBody();

        $result = $this->articleService->createArticle($params);
        if ($result === true) {
            return $response
                ->withHeader('Location', '/admin/article')
                ->withStatus(302);
            $this->app->redirect("/admin/article");
        } else {
            $this->container->get('flash')->addMessage('error', 'Cannot create article');
            $this->container->get('flash')->addMessage('article', $result);
            return $response
                ->withHeader('Location', '/admin/article')
                ->withStatus(302);
        }
    }

    public function route($args)
    {
        // figure out what's going on here.
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