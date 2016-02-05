<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Model;


use Illuminate\Database\Eloquent\Model;

class ZineIssue extends Model
{
    protected $table = 'zine_issue';

    public function article()
    {
        return $this->hasOne('Sloop\Model\Article', 'issue_id');
    }

    public function zine()
    {
        return $this->belongsTo('Sloop\Model\Zine');
    }
}