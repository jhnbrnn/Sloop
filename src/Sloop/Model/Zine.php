<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Model;


use Illuminate\Database\Eloquent\Model;

class Zine extends Model
{
    protected $table = 'zine';

    public function zineIssues()
    {
        return $this->hasMany('Sloop\Model\ZineIssue');
    }

}