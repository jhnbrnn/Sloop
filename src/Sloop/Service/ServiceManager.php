<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Service;

use Psr\Container\ContainerInterface;
use Sloop\Sloop;
use Sloop\User\UserService;
use Sloop\Service\ArticleService;
use Sloop\Service\ZineService;
use Sloop\Service\UrlService;

class ServiceManager
{
    public static function registerServices(ContainerInterface $container)
    {
        $container->set('UserService', function () {
            return new UserService();
        });

        $container->set('ArticleService', function() use ($container) {
            return new ArticleService($container);
        });

        $container->set('ZineService', function() use ($container) {
            return new ZineService($container);
        });

        $container->set('UrlService', function() use ($container) {
            return new UrlService($container);
        });
    }
}