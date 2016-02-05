<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin;


use Slim\Slim;
use Sloop\Sloop;

class AdminManager
{

    protected static $controllers = [
        'LoginController',
        'ArticleAdminController',
        'ZineAdminController',
        'ZineIssueAdminController',
        'MiscAdminController'
    ];

    protected static $appControllers = [
        'LoginFormController',
        'LogoutFormController',
        'ArticleFormController',
        'ZineIssueFormController'
    ];

    /**
     * @param $appInstance Sloop
     */
    public static function registerControllers($appInstance)
    {
        foreach (self::$controllers as $controllerName) {
            $appInstance->registerService($controllerName,
                __NAMESPACE__ . "\\Controller\\View\\" . $controllerName);
        }
    }

    /**
     * @param $appInstance Sloop
     */
    public static function registerAppControllers($appInstance)
    {
        foreach (self::$appControllers as $controllerName) {
            $appInstance->registerService($controllerName,
                __NAMESPACE__ . "\\Controller\\App\\" . $controllerName);
        }
    }

    /**
     * @param $appInstance Sloop
     * @param $slim Slim
     */
    public static function registerRoutes($appInstance, $slim)
    {
        $slim->get('/admin', $appInstance->LoginController);
        $slim->get('/admin/article(/:id)',
            $appInstance->ArticleAdminController);
        $slim->get('/admin/zine/:id/zine-issue(/:issueId)',
            $appInstance->ZineIssueAdminController);
        $slim->get('/admin/zine(/:id)', $appInstance->ZineAdminController);
        $slim->get('/admin/:route+', $appInstance->MiscAdminController);

        $slim->post('/app/login', $appInstance->LoginFormController);
        $slim->post('/app/logout', $appInstance->LogoutFormController);
        $slim->post('/app/article', $appInstance->ArticleFormController);
        $slim->post('/app/zine-issue', $appInstance->ZineIssueFormController);
    }

}