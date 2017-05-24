<?php
/**
 * Created by PhpStorm.
 * User: fm
 * Date: 2017/4/8
 * Time: 16:15
 */

namespace App;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public static function getMember()
    {
        return 'member model method is called!';
    }


}