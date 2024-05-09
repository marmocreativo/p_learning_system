<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Cuenta;
use App\Models\Distribuidor;
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

        $loginType = filter_var($request->Email, FILTER_VALIDATE_EMAIL) ? 'email' : 'legacy_id';

        // validation
        $credentials = [
            $loginType=> $request->Email,
            "password"=> $request->Password,
            //"estado"=> 'activo'
        ];



        //$remember = ($request->has('remember') ? true : false);
        
        $remember = false;

        if(Auth::attempt($credentials, $remember)){

            $user = User::where($loginType, $request->Email)->firstOrFail();

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

    public function login_suscripcion_api(Request $request){

        $loginType = filter_var($request->Email, FILTER_VALIDATE_EMAIL) ? 'email' : 'legacy_id';

        // validation
        $credentials = [
            $loginType=> $request->Email,
            "password"=> $request->Password,
            //"estado"=> 'activo'
        ];



        //$remember = ($request->has('remember') ? true : false);
        
        $remember = false;

        if(Auth::attempt($credentials, $remember)){

            $user = User::where($loginType, $request->Email)->firstOrFail();
            $id_usuario = $user->id;
            $id_cuenta = $request->input('id_cuenta');
            $cuenta = Cuenta::find($id_cuenta);
            $id_temporada = $cuenta->temporada_actual;
            $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
            $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();

            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Hola '.$user->nombre,
                'accessToken' => $token,
                'token_type' => 'Bearer',
                'user'=>$user,
                'distribuidor'=>$distribuidor->nombre,
                'region'=>$distribuidor->region,
            ]);

        }else{
            
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
    }

    public function check_login_api(Request $request){

        $user = User::find($request->input('id'));
        return response()->json($user);

    }

    public function full_check_api(Request $request){

        $user = User::find($request->input('id'));
        $suscripcion = UsuariosSuscripciones::where('id_usuario', $request->input('id'))->where('id_temporada', $request->input('id_temporada'))->first();
        $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
        $completo = [
            'usuario'=> $user,
            'sucripcion' => $suscripcion,
            'distribuidor' => $distribuidor
        ];
        return response()->json($completo);

    }

    public function logout_api()
    {
        auth()->user()->tokens()->delete();
        return [
            'message'=> 'Has cerrado sesión correctamente'
        ];
    }

    
}
