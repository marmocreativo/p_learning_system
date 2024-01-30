<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __invoke() {
        //return "Controlador de inicio";
    
        return view('admin/home');

    }
}
