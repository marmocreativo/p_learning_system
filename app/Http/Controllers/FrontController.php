<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function __invoke() {
        //return "Controlador de inicio";
        return view('front/home');
    }
}
