<?php
/**
 * Created by PhpStorm.
 * User: hzw
 * Date: 2017/5/23
 * Time: 22:16
 */

namespace App\Http\Controllers\Utility;

use Illuminate\Database\Connection;

// 单例类
class DBConnectinHandler
{
    private static $__instance = null;

    public static function getInstance(){
        if(self::$__instance == null){
            self::$__instance = new DBConnectinHandler();
        }
        return self::$__instance;
    }

    public function connection($dbIp, $database, $user, $password)
    {
        $dsn = "mysql:host={$dbIp};dbname={$database}";
        $db = new \PDO($dsn, $user, $password);
        $con = new Connection($db);
        return $con;
    }
}