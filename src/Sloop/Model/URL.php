<?php

namespace Sloop\Model;


use Illuminate\Database\Eloquent\Model;

class URL extends Model
{
    protected $table = 'url';
    public $timestamps = false;

    public function article()
    {
        return $this->belongsTo('Sloop\Model\Article', 'content_id');
    }

}