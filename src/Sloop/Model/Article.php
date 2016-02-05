<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'article';
    protected $dateFormat = 'U';

    public function url()
    {
        return $this->hasMany('Sloop\Model\URL', 'content_id');
    }

    public function zineIssue()
    {
        return $this->belongsTo('Sloop\Model\ZineIssue', 'issue_id');
    }
}