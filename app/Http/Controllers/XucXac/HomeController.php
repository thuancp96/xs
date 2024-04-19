<?php

namespace App\Http\Controllers\XucXac;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Controller for dice game
    |--------------------------------------------------------------------------
    */
    public function index(){
        return view('xucxac.index');
    }

}