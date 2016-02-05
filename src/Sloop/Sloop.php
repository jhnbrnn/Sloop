<?php
namespace Sloop;

use Illuminate\Database\Capsule\Manager as DatabaseManager;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Pimple\Container;
use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Sloop\Admin\AdminManager;
use Sloop\Service\ServiceManager;
use Sloop\User\UserService;

class Sloop
{
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var Slim
     */
    protected $app;

    public function __construct($settings)
    {
        $this->container = new Container();
        $db = new DatabaseManager();
        $db->addConnection($settings['db']['mysql']);
        $db->setAsGlobal();
        $db->bootEloquent();
        $this->app = $this->getFramework($settings['slim']);
        $this->setLogger($this->container);
        $this->setUserService($this->container);
        ServiceManager::registerServices($this);
        AdminManager::registerControllers($this);
        AdminManager::registerAppControllers($this);
        AdminManager::registerRoutes($this, $this->app);
    }

    protected function getFramework($config)
    {
        $app = new Slim([
            'view' => new Twig()
        ]);

        $app->config([
            'templates.path' => $config['templates.path']
        ]);

        $view = $app->view();
        $view->parserOptions = $config['parserOptions'];
        $view->parserExtensions = array(
            new TwigExtension()
        );
        return $app;
    }

    public function getSlim()
    {
        return $this->app;
    }

    protected function setLogger(&$c)
    {
        $c['Logger'] = function () {
            $log = new Logger('ErrorLogger');
            $handler = new ErrorLogHandler();
            $formatter = new LineFormatter();
            $formatter->includeStacktraces();
            $handler->setFormatter($formatter);
            $log->pushHandler($handler);
            return $log;
        };
    }

    protected function setUserService(&$c)
    {
        $c['UserService'] = function () {
            return new UserService();
        };
    }

    public function __get($name)
    {
        if (in_array($name, $this->container->keys())) {
            $service = $this->container[$name];
            if (is_a($service, 'Sloop\Controller\AbstractController')) {
                return $service->getRoute();
            } else {
                return $service;
            }
        }
        return null;
    }

    /**
     * @param string $serviceName
     * @param mixed $callable
     */
    public function registerService($serviceName, $callable = null)
    {
        if (is_string($callable)) {
            error_log('attempting to register ' . $serviceName);
            /* TODO Rethink how this works in order to figure out parameters to pass to new registered service */
            $this->container[$serviceName] =
                function ($c) use ($callable) {
                    $r = new \ReflectionClass($callable);
                    return $r->newInstanceArgs([$c]);
                };
        } elseif (is_callable($callable)) {
            $this->container->offsetSet($serviceName, $callable);
        }
    }

    public function getZine($id)
    {
        return $this->container['ZineService']->getZine($id);
    }

    public function getZineIssue($zineId, $id)
    {
        return $this->container['ZineService']->getZineIssue($zineId, $id);
    }

    public function getAllZines()
    {
        return $this->container['ZineService']->getAll();
    }

    public function getAllIssuesForZine($zineId)
    {
        return $this->container['ZineService']->getIssuesForZine($zineId);
    }

    public function createZine($title, $description)
    {
        return $this->container['ZineService']->createZine($title,
            $description);
    }

    public function createZineIssue($zineId, $zineIssueArray)
    {
        return $this->container['ZineService']->createZineIssue($zineId,
            $zineIssueArray);
    }

    public function getArticleIdByUrl($url)
    {
        return $this->container['UrlService']->getIdByUrl($url);
    }

    public function getArticle($id)
    {
        return $this->container['ArticleService']->getArticle($id);
    }

    public function getAllArticles()
    {
        return $this->container['ArticleService']->getAll();
    }

    public function getLatestArticle()
    {
        return $this->container['ArticleService']->getLatestArticle();
    }

    public function createArticle($articleArray)
    {
        if (isset($articleArray['articleId'])) {
            return $this->container['ArticleService']->update($articleArray);
        } else {
            return $this->container['ArticleService']->create($articleArray);
        }

    }
}