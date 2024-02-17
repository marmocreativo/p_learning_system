<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    //

    public function login_form(Request $request){

        // validation
        return view('login/login');

        
    }

    public function login_verificar(Request $request){

        // validation
        $credentials = [
            "email"=> $request->Email,
            "password"=> $request->Password,
            //"estado"=> 'activo'
        ];

        //$remember = ($request->has('remember') ? true : false);
        $remember = false;

        if(Auth::attempt($credentials, $remember)){

            return redirect()->intended('admin');

        }else{
            
            return redirect(route('login'));
        }

        
    }

    public function logout(Request $request){
    
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

    /* Funciones de registro */
    public function registro_form(Request $request){

        // validation
        return view('login/registro_form');

        
    }

    public function registro(Request $request){

        // validation
        $usuario = new User();
        
        $usuario->legacy_id = uniqid('',true);
        $usuario->nombre = $request->Nombre;
        $usuario->apellidos = $request->Apellidos;
        $usuario->email = $request->Email;
        $usuario->password = Hash::make($request->Password);

        $usuario->save();

        Auth::login($usuario);

        return redirect(route('admin'));
        
    }

    
}
