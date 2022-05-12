<?php
namespace Sloop;

use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;

use Slim\App;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Views\TwigExtension;
use Slim\Factory\AppFactory;
use Sloop\Admin\AdminManager;
use Sloop\Service\ServiceManager;

class Sloop
{
    /**
     * @var Container
     */
    protected $container;

    protected $appContainer;
    /**
     * @var Slim
     */
    protected $app;

    public function __construct($settings)
    {
        $this->container = new Container();
        $this->appContainer = new \DI\Container();
        $db = new Capsule();
        $db->addConnection($settings['db']['mysql']);
        $db->setAsGlobal();
        $db->bootEloquent();
        $this->app = $this->getFramework($settings['slim']);
        $this->setLogger($this->container);

        ServiceManager::registerServices($this->appContainer);
        // AdminManager::registerControllers($this);
        // AdminManager::registerAppControllers($this);
        AdminManager::registerRoutes($this, $this->app);
    }

    protected function getFramework($config)
    {
        AppFactory::setContainer($this->appContainer);

        $this->appContainer->set('view', function() use ($config) {
            return Twig::create($config['templates.path'], ['cache' => $config['parserOptions']['cache']]);
        });

        $this->appContainer->set('flash', function() {
            $storage = [];
            return new Messages($storage);
        });

        $app = AppFactory::create();
        $app->add(TwigMiddleware::createFromContainer($app));

       // Add session start middleware
        $app->add(function ($request, $next) {
            // Start PHP session
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            // Change flash message storage
            $this->get('flash')->__construct($_SESSION);

            return $next->handle($request);
        }); 

        $app->addErrorMiddleware(true, true, true);

        return $app;
    }

    public function getSlim()
    {
        return $this->app;
    }

    public function getView()
    {
        return $this->app->getContainer()->get('view');
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
        $this->appContainer->set('logger', function () {
            $log = new Logger('ErrorLogger');
            $handler = new ErrorLogHandler();
            $formatter = new LineFormatter();
            $formatter->includeStacktraces();
            $handler->setFormatter($formatter);
            $log->pushHandler($handler);
            return $log;
        });
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
        return $this->appContainer->get('ZineService')->getZine($id);
    }

    public function getZineIssue($zineId, $id)
    {
        return $this->appContainer->get('ZineService')->getZineIssue($zineId, $id);
    }

    public function getAllZines()
    {
        return $this->appContainer->get('ZineService')->getAll();
    }

    public function getAllIssuesForZine($zineId)
    {
        return $this->appContainer->get('ZineService')->getIssuesForZine($zineId);
    }

    public function createZine($title, $description)
    {
        return $this->appContainer->get('ZineService')->createZine($title,
            $description);
    }

    public function createZineIssue($zineId, $zineIssueArray)
    {
        return $this->appContainer->get('ZineService')->createZineIssue($zineId,
            $zineIssueArray);
    }

    public function getArticleIdByUrl($url)
    {
        return $this->appContainer->get('UrlService')->getIdByUrl($url);
    }

    public function getArticle($id)
    {
        return $this->appContainer->get('ArticleService')->getArticle($id);
    }

    public function getAllArticles()
    {
        return $this->appContainer->get('ArticleService')->getAll();
    }

    public function getLatestArticle()
    {
        return $this->appContainer->get('ArticleService')->getLatestArticle();
    }

    public function createArticle($articleArray)
    {
        if (isset($articleArray['articleId'])) {
            return $this->appContainer->get('ArticleService')->update($articleArray);
        } else {
            return $this->appContainer->get('ArticleService')->create($articleArray);
        }

    }
}