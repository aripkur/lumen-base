<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WebController {

    public function login(){
        return view("login");
    }

    public function dashboard(){
        return view("dashboard");
    }
}
