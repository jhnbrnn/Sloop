<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Admin;

use Slim\App;
use Sloop\Sloop;

class AdminManager
{

    // protected static $controllers = [
    //     'LoginController',
    //     // 'ArticleAdminController',
    //     // 'ZineAdminController',
    //     // 'ZineIssueAdminController',
    //     // 'MiscAdminController'
    // ];

    // protected static $appControllers = [
    //     'LoginFormController',
    //     'LogoutFormController',
    //     'ArticleFormController',
    //     'ZineIssueFormController'
    // ];

    // /**
    //  * @param $appInstance Sloop
    //  */
    // public static function registerControllers($appInstance)
    // {
    //     foreach (self::$controllers as $controllerName) {
    //         $appInstance->registerService($controllerName,
    //             __NAMESPACE__ . "\\Controller\\View\\" . $controllerName);
    //     }
    // }

    // /**
    //  * @param $appInstance Sloop
    //  */
    // public static function registerAppControllers($appInstance)
    // {
    //     foreach (self::$appControllers as $controllerName) {
    //         $appInstance->registerService($controllerName,
    //             __NAMESPACE__ . "\\Controller\\App\\" . $controllerName);
    //     }
    // }

    /**
     * @param $appInstance Sloop
     * @param $slim Slim
     */
    public static function registerRoutes($appInstance, $slim)
    {
        $slim->get('/admin', Controller\View\LoginController::class);
        $slim->get('/admin/article[/{id}]', Controller\View\ArticleAdminController::class);
        $slim->get('/admin/zine[/{id}]', Controller\View\ZineAdminController::class);
        $slim->get('/admin/zine/{id}/zine-issue[/{issueId}]', Controller\View\ZineIssueAdminController::class);
        $slim->get('/admin/{params:.*}', Controller\View\MiscAdminController::class);

        $slim->post('/app/login', Controller\App\LoginFormController::class);
        $slim->post('/app/logout', Controller\App\LogoutFormController::class);
        $slim->post('/app/article', Controller\App\ArticleFormController::class);
        $slim->post('/app/zine-issue', Controller\App\ZineIssueFormController::class);
    }

}