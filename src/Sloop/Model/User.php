<?php
/**
 * Author: johnbrennan
 */

namespace Sloop\Model;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $primaryKey = 'user_id';
    public $timestamps = false;

}