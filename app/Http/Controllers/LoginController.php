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

    public function login_api(Request $request){

        // validation
        $credentials = [
            "email"=> $request->Email,
            "password"=> $request->Password,
            //"estado"=> 'activo'
        ];



        //$remember = ($request->has('remember') ? true : false);
        
        $remember = false;

        if(Auth::attempt($credentials, $remember)){

            $user = User::where('email', $request->Email)->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Hola '.$user->nombre,
                'accessToken' => $token,
                'token_type' => 'Bearer',
                'user'=>$user,
            ]);

        }else{
            
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        

        
    }

    public function check_login_api(Request $request){

        // Obtén el usuario autenticado
        $user = Auth::user();

        // O, si necesitas el ID del usuario, puedes obtenerlo así:
        // $userId = $request->user()->id;
        // $user = User::find($userId);

        // Retorna los datos del usuario
        return response()->json([
            'user' => $user
        ]);
    }

    public function logout_api()
    {
        auth()->user()->tokens()->delete();
        return [
            'message'=> 'Has cerrado sesión correctamente'
        ];
    }

    
}
