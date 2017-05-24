<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;


class AjaxController extends Controller
{
    public function responseGet()
    {
        $msg = "this is a easy msg.";

        return response()->json(array('msg'=>$msg), 200);
    }

    public function responsePost(Request $request)
    {
        //$username = $request->get('username');
        //$username = $request->input('username');
        // or
        $username = Input::get('username');
        return response()->json(array('msg'=>$username), 200);
    }
}
