<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Service;


use Sloop\Model\URL;

class UrlService extends AbstractService
{

    public function getIdByUrl($path)
    {
        $url = URL::query()->where('path', $path)->where('active', 1)->first();
        return $url->content_id;
    }

}