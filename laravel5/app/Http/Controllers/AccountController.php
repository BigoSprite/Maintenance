<?php
/**
 * Created by PhpStorm.
 * User: fm
 * Date: 2017/5/16
 * Time: 20:10
 */

namespace App\Http\Controllers;

use Input;
use Request;
class AccountController extends Controller
{
    public function login()
    {

        return "success___";
        // Getting all post data
        //if (Request::ajax()) {
        //    $data = Input::all();
        //    print_r($data);
        //    die;
        //}
    }
}