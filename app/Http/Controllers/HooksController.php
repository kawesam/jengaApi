<?php

namespace App\Http\Controllers;

use App\Helpers\JengaApi;
use Illuminate\Http\Request;

class HooksController extends Controller
{
    //

    //method to generate jenga APi token
    public function generateToken(){
        $token = JengaApi::generateToken();
        return $token;
    }
}
