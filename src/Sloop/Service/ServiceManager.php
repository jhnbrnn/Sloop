<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Service;


use Sloop\Sloop;

class ServiceManager
{
    protected static $services = [
        'ArticleService',
        'ZineService',
        'UrlService'
    ];

    /**
     * @param $appInstance Sloop
     */
    public static function registerServices($appInstance)
    {
        foreach (self::$services as $serviceName) {
            $appInstance->registerService($serviceName,
                __NAMESPACE__ . '\\' . $serviceName);
        }
    }
}