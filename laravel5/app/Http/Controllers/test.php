<?php
/**
 * Created by PhpStorm.
 * User: fm
 * Date: 2017/5/15
 * Time: 18:00
 */

class TestController extends \App\Http\Controllers\Controller
{
    // 私有变量+
    private $__a = '';

    // 构造函数
    public function __construct()
    {

    }

    // 初始化私有变量的方法
    //  我的需求是必须先调用这个方法；然后在调用getA方法，怎么让getA返回的是赋了的值，不是''呢
    public function initialize($val){
        $__a = $val;
    }

    // 获取私有变量的值
    public function getA()
    {
        return $this->__a;// 这里还是null，，，，不是变量$val的值？
    }

}