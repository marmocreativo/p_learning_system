<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Distribuidor;
use App\Models\DistribuidorSuscripciones;
use App\Models\SesionVis;
use App\Models\SesionEv;
use App\Models\EvaluacionRes;
use App\Models\PuntosExtra;
use App\Models\TriviaGanador;
use App\Models\TriviaRes;
use App\Models\Trivia;
use App\Models\JackpotIntentos;
use App\Models\JackpotRes;
use App\Models\Jackpot;
use App\Models\Cuenta;
use App\Models\Tokens;
use App\Models\IntentoLogin;
use App\Models\AccionesUsuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

use App\Mail\RestaurarPass;
use App\Mail\CambioPass;
use Illuminate\Support\Facades\Mail;


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

            $accion = new AccionesUsuarios();
            $accion->id_usuario = $user->id;
            $accion->nombre = $user->nombre.''.$user->apellidos;
            $accion->correo = $user->email;
            $accion->accion = 'login';
            $accion->descripcion = 'Se inicio sesión via API';
            $accion->save();
            
    
            return response()->json([
                'message' => 'Hola '.$user->nombre,
                'accessToken' => $token,
                'token_type' => 'Bearer',
                'user'=>$user,
            ]);

        }else{
            
            return response()->json(['message' => 'No autorizado'], 401);
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
            
            
            if(!empty($suscripcion)){
                $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
                $token = $user->createToken('auth_token')->plainTextToken;

                $accion = new AccionesUsuarios();
                $accion->id_usuario = $user->id;
                $accion->nombre = $user->nombre.''.$user->apellidos;
                $accion->correo = $user->email;
                $accion->accion = 'login';
                $accion->descripcion = 'Se inicio sesión via API';
                $accion->id_cuenta = $id_cuenta;
                $accion->id_temporada = $suscripcion->id_temporada;
                $accion->funcion = 'usuario';
                $accion->save();
                
                return response()->json([
                    'message' => 'Hola '.$user->nombre,
                    'accessToken' => $token,
                    'token_type' => 'Bearer',
                    'user'=>$user,
                    'distribuidor'=>$distribuidor->nombre,
                    'region'=>$distribuidor->region,
                ]);
            }else{
                return response()->json(['message' => 'No estás participando en este programa.'], 401);
            }
            

        }else{
            
            return response()->json(['message' => 'Las credenciales no coinciden'], 401);
        }
        
    }

    public function login_gate_api(Request $request){
        $pl_electrico = Cuenta::find(1);
        $pl_ni = Cuenta::find(3);

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
            $suscripcion_electrico = UsuariosSuscripciones::where('id_temporada', $pl_electrico->temporada_actual)->where('id_usuario', $id_usuario)->first();
            $suscripcion_ni = UsuariosSuscripciones::where('id_temporada', $pl_ni->temporada_actual)->where('id_usuario', $id_usuario)->first();
            $temporada_accion = null;
            $redireccion = 'ninguna';
            switch ($request->id_cuenta) {
                case '1':
                    if($suscripcion_electrico&&$suscripcion_ni){
                        $redireccion = 'gate_doble';
                    }
                    
                    if($suscripcion_electrico&&!$suscripcion_ni){
                        $redireccion = 'usuario';
                    }
                    if(!$suscripcion_electrico&&$suscripcion_ni){
                        $redireccion = 'gate_ni';
                    }

                    $temporada_accion = $pl_electrico->temporada_actual;
                    break;

                case '3':
                    if($suscripcion_electrico&&$suscripcion_ni){
                        $redireccion = 'gate_doble';
                    }
                    if($suscripcion_electrico&&!$suscripcion_ni){
                        $redireccion = 'gate_electrico';
                    }
                    if(!$suscripcion_electrico&&$suscripcion_ni){
                        $redireccion = 'usuario';
                    }
                    $temporada_accion = $pl_ni->temporada_actual;
                    break;
                
                default:
                    $redireccion = 'ninguna';
                    break;
            }
            
                if($suscripcion_electrico){
                    $distribuidor_electrico = Distribuidor::where('id', $suscripcion_electrico->id_distribuidor)->first();
                    $nombre_dist_el = $distribuidor_electrico->nombre;
                    $region_dist_el = $distribuidor_electrico->region;
                }else{
                    $distribuidor_electrico = null;
                    $nombre_dist_el = '';
                    $region_dist_el = '';
                }

                if($suscripcion_ni){
                    $distribuidor_ni = Distribuidor::where('id', $suscripcion_ni->id_distribuidor)->first();
                    $nombre_dist_ni = $distribuidor_ni->nombre;
                    $region_dist_ni = $distribuidor_ni->region;
                }else{
                    $distribuidor_ni = null;
                    $nombre_dist_ni = '';
                    $region_dist_ni = '';
                }

                
                $token = $user->createToken('auth_token')->plainTextToken;

                $accion = new AccionesUsuarios();
                $accion->id_usuario = $user->id;
                $accion->nombre = $user->nombre.''.$user->apellidos;
                $accion->correo = $user->email;
                $accion->accion = 'login';
                $accion->descripcion = 'Se inicio sesión via API';
                $accion->id_cuenta = $request->id_cuenta;
                $accion->id_temporada = $temporada_accion;
                $accion->funcion = 'usuario';
                $accion->save();
                
                return response()->json([
                    'message' => 'Hola '.$user->nombre,
                    'redireccion' => $redireccion,
                    'accessToken' => $token,
                    'token_type' => 'Bearer',
                    'user'=>$user,
                    'distribuidor_electrico'=>$nombre_dist_el,
                    'distribuidor_ni'=>$nombre_dist_ni,
                    'region_electrico'=>$region_dist_el,
                    'region_ni'=>$region_dist_ni,
                ]);
            

        }else{
            
            return response()->json(['message' => 'Las credenciales no coinciden'], 401);
        }
        
    }

    public function check_login_api(Request $request){

        $user = User::find($request->input('id'));
        return response()->json($user);

    }

    /*
    public function olvide_pass_api(Request $request)
    {
        $user = User::where('email', $request->input('Email'))->first();
        if($user){
            $id_cuenta = $request->input('id_cuenta');
            $cuenta = Cuenta::find($id_cuenta);
            $suscripcion = UsuariosSuscripciones::where('id_usuario', $user->id)->where('id_temporada', $cuenta->temporada_actual)->first();
            $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
            if($id_cuenta==1){
                $data = [
                    'banner' => 'https://p-learning.panduitlatam.com/assets/images/micrositio/1600x-300-Email-Banner-PLe.jpg',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/login/restaurar/'.$user->id.'/'.$distribuidor->id
                ];
            }else{
                $data = [
                    'banner' => 'https://p-learning.panduitlatam.com/assets/images/micrositio/1600x-300-Email-Banner-PL.jpg',
                    'boton_enlace' => 'https://p-learning.panduitlatam.com/login/restaurar/'.$user->id.'/'.$distribuidor->id
                ];
            }
           
            Mail::to($user->email)->send(new RestaurarPass($data));
        }

    }
        */
        public function olvide_pass_api(Request $request){
            try {
                $user = User::where('email', $request->input('Email'))->first();
                if($user){
                    $id_cuenta = $request->input('id_cuenta');
                    $cuenta = Cuenta::find($id_cuenta);
                    $suscripcion = UsuariosSuscripciones::where('id_usuario', $user->id)->where('id_temporada', $cuenta->temporada_actual)->first();
                    $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();
                    
                    if($id_cuenta==1){
                        $data = [
                            'banner' => 'https://p-learning.panduitlatam.com/assets/images/micrositio/1600x-300-Email-Banner-PLe.jpg',
                            'boton_enlace' => 'https://pl-electrico.panduitlatam.com/login/restaurar/'.$user->id.'/'.$distribuidor->id
                        ];
                    }else{
                        $data = [
                            'banner' => 'https://p-learning.panduitlatam.com/assets/images/micrositio/1600x-300-Email-Banner-PL.jpg',
                            'boton_enlace' => 'https://p-learning.panduitlatam.com/login/restaurar/'.$user->id.'/'.$distribuidor->id
                        ];
                    }
                   
                    try {
                        Mail::to($user->email)->send(new RestaurarPass($data));
                    } catch (\Exception $e) {
                        // Registrar el error pero continuar con la ejecución
                        \Log::error('Error al enviar correo de restauración de contraseña: ' . $e->getMessage());
                    }
                    
                    return response()->json(['success' => true]);
                }
                
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado']);
                
            } catch (\Exception $e) {
                \Log::error('Error en olvide_pass_api: ' . $e->getMessage());
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    
        /*
    public function restaurar_pass_api(Request $request){
       

        $usuario = User::find($request->input('id'));
        $id_distribuidor = $request->input('di');
        $distribuidor = Distribuidor::find($request->input('di'));
        $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_distribuidor', $id_distribuidor)->first();
        

        

        $usuario->password = Hash::make($distribuidor->default_pass);
        $usuario->save();
        if(!empty($suscripcion)&&$suscripcion->id_distribuidor==1){
            $data = [
                'newpass' => $distribuidor->default_pass,
                'boton_enlace' => 'https://pl-electrico.panduitlatam.com/login'
            ];
        }else{
            $data = [
                'newpass' => $distribuidor->default_pass,
                'boton_enlace' => 'https://p-learning.panduitlatam.com/login'
            ];
        }
        
        Mail::to($usuario->email)->send(new CambioPass($data));

        
        return response()->json($distribuidor->default_pass);

    }
        */ 

        public function restaurar_pass_api(Request $request){
            try {
                $usuario = User::find($request->input('id'));
                $id_distribuidor = $request->input('di');
                $distribuidor = Distribuidor::find($request->input('di'));
                $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_distribuidor', $id_distribuidor)->first();
                
                // Primero guardamos la nueva contraseña
                $usuario->password = Hash::make($distribuidor->default_pass);
                $usuario->save();
                
                // Preparamos los datos para el correo
                if(!empty($suscripcion) && $suscripcion->id_distribuidor==1){
                    $data = [
                        'newpass' => $distribuidor->default_pass,
                        'boton_enlace' => 'https://pl-electrico.panduitlatam.com/login'
                    ];
                } else {
                    $data = [
                        'newpass' => $distribuidor->default_pass,
                        'boton_enlace' => 'https://p-learning.panduitlatam.com/login'
                    ];
                }
                
                // Intentamos enviar el correo, pero capturamos la excepción si ocurre
                try {
                    Mail::to($usuario->email)->send(new CambioPass($data));
                } catch (\Exception $e) {
                    // Registramos el error pero continuamos con la ejecución
                    \Log::error('Error al enviar correo de cambio de contraseña: ' . $e->getMessage());
                }
                
                return response()->json($distribuidor->default_pass);
                
            } catch (\Exception $e) {
                \Log::error('Error en restaurar_pass_api: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
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

    public function full_check_puntaje_api(Request $request){
        
        $id_usuario = $request->input('id');
        $user = User::find($request->input('id'));
        $id_temporada = $request->input('id_temporada');
        $suscripcion = UsuariosSuscripciones::where('id_usuario', $request->input('id'))->where('id_temporada', $request->input('id_temporada'))->first();
        $distribuidor = Distribuidor::where('id', $suscripcion->id_distribuidor)->first();

        $visualizaciones = SesionVis::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $trivia = TriviaRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $extra = PuntosExtra::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntos')->sum();


        $puntajes = [
            'visualizaciones' =>$visualizaciones,
            'evaluaciones' =>$evaluaciones,
            'trivia' =>$trivia,
            'jackpots' =>$jackpots,
            'extra' =>$extra,
        ];


        $completo = [
            'usuario'=> $user,
            'sucripcion' => $suscripcion,
            'distribuidor' => $distribuidor,
            'puntaje' =>$puntajes
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

    /**
     * API 2025
     */

     public function check_token_api(Request $request)
    {
        // Obtén el token desde los parámetros de la consulta
        $token = $request->query('token');

        if (!$token) {
            return response()->json(['valid' => false, 'message' => 'Token no proporcionado'], 400);
        }

        // Busca el usuario por el token (ajusta esto según tu lógica de tokens)
        $personalAccessToken = PersonalAccessToken::findToken($token);

        if ($personalAccessToken) {
            // El token es válido, puedes obtener el usuario asociado
            $user = $personalAccessToken->tokenable; // tokenable contiene al usuario asociado
    
            return response()->json([
                'valid' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], 200);
        }

        // El token no es válido
        return response()->json(['valid' => false, 'message' => 'Token inválido'], 401);
    }

    public function login_gate_2025_api(Request $request){
        $pl_el = Cuenta::find(1);
        $pl_ni = Cuenta::find(3);
        $pl_et = Cuenta::find(4);
        $pl_test = Cuenta::find(5);

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
            $suscripcion_el = UsuariosSuscripciones::where('id_temporada', $pl_el->temporada_actual)->where('id_usuario', $id_usuario)->first();
            $suscripcion_ni = UsuariosSuscripciones::where('id_temporada', $pl_ni->temporada_actual)->where('id_usuario', $id_usuario)->first();
            $suscripcion_et = UsuariosSuscripciones::where('id_temporada', $pl_et->temporada_actual)->where('id_usuario', $id_usuario)->first();
            $suscripcion_test = UsuariosSuscripciones::where('id_temporada', $pl_test->temporada_actual)->where('id_usuario', $id_usuario)->first();
            $redireccion = 'ninguna';

            $temporada_accion = null;


            switch ($request->id_cuenta) {
                case '1':
                    if($suscripcion_el&&$suscripcion_ni){
                        $redireccion = 'gate_doble';
                    }
                    if($suscripcion_el&&!$suscripcion_ni){
                        $redireccion = 'usuario';
                    }
                    if(!$suscripcion_el&&$suscripcion_ni){
                        $redireccion = 'gate_ni';
                    }
                    $temporada_accion = $pl_el->temporada_actual;
                    break;

                case '3':
                    if($suscripcion_el&&$suscripcion_ni){
                        $redireccion = 'gate_doble';
                    }
                    if($suscripcion_el&&!$suscripcion_ni){
                        $redireccion = 'gate_electrico';
                    }
                    if(!$suscripcion_el&&$suscripcion_ni){
                        $redireccion = 'usuario';
                    }
                    $temporada_accion = $pl_ni->temporada_actual;
                    break;
                case '4':
                    if($suscripcion_et){
                        $redireccion = 'usuario';
                    }else{
                        if($suscripcion_el&&$suscripcion_ni){
                            $redireccion = 'gate_doble';
                        }
                        if($suscripcion_el&&!$suscripcion_ni){
                            $redireccion = 'gate_electrico';
                        }
                        if(!$suscripcion_el&&$suscripcion_ni){
                            $redireccion = 'gate_ni';
                        }
                    }
                    $temporada_accion = $pl_et->temporada_actual;
                    
                    break;
                case '5':
                    if($suscripcion_test){
                        $redireccion = 'usuario';
                    }else{
                        if($suscripcion_el&&$suscripcion_ni){
                            $redireccion = 'gate_doble';
                        }
                        if($suscripcion_el&&!$suscripcion_ni){
                            $redireccion = 'gate_electrico';
                        }
                        if(!$suscripcion_el&&$suscripcion_ni){
                            $redireccion = 'gate_ni';
                        }
                    }
                    $temporada_accion = $pl_test->temporada_actual;
                    break;
                
                default:
                    $redireccion = 'ninguna';
                    break;
            }
            
                if($suscripcion_el){
                    $distribuidor_electrico = $suscripcion_el->distribuidor;
                    $nombre_dist_el = $suscripcion_el->distribuidor->nombre;
                    $region_dist_el = $suscripcion_el->distribuidor->region;
                }else{
                    $distribuidor_electrico = null;
                    $nombre_dist_el = '';
                    $region_dist_el = '';
                }

                if($suscripcion_ni){
                    $distribuidor_ni = $suscripcion_ni->distribuidor;
                    $nombre_dist_ni = $suscripcion_ni->distribuidor->nombre;
                    $region_dist_ni = $suscripcion_ni->distribuidor->region;
                }else{
                    $distribuidor_ni = null;
                    $nombre_dist_ni = '';
                    $region_dist_ni = '';
                }

                if($suscripcion_et){
                    $distribuidor_et = $suscripcion_et->distribuidor;
                    $nombre_dist_et = $suscripcion_et->distribuidor->nombre;
                    $region_dist_et = $suscripcion_et->distribuidor->region;
                }else{
                    $distribuidor_et = null;
                    $nombre_dist_et = '';
                    $region_dist_et = '';
                }

                if($suscripcion_test){
                    $distribuidor_test = $suscripcion_test->distribuidor;
                    $nombre_dist_test = $suscripcion_test->distribuidor->nombre;
                    $region_dist_test = $suscripcion_test->distribuidor->region;
                }else{
                    $distribuidor_test = null;
                    $nombre_dist_test = '';
                    $region_dist_test = '';
                }


                
                $token = $user->createToken('auth_token')->plainTextToken;

                /* Este fragmento guarda una acción agregar en cualquier acción que se desee guardar */
                $accion = new AccionesUsuarios();
                $accion->id_usuario = $user->id;
                $accion->nombre = $user->nombre.''.$user->apellidos;
                $accion->correo = $user->email;
                $accion->accion = 'login';
                $accion->descripcion = 'Se inicio sesión via API';
                $accion->id_cuenta = $request->id_cuenta;
                $accion->id_temporada = $temporada_accion;
                $accion->funcion = 'usuario';
                $accion->save();
                /* Este fragmento guarda una acción agregar en cualquier acción que se desee guardar */

                // Premio de primer acceso
                //
                $primer_acceso = false;
                $mensaje_primer_acceso = '';
                switch ($request->id_cuenta) {
                    case '1':
                        $mensaje_primer_acceso .= 'Caso 1 -';
                        $cuenta = $pl_el;
                        if($cuenta->bono_login=='si'){
                            $mensaje_primer_acceso .= 'Si hay bono activo -';
                            //Buscar si ya se registraron los puntos
                            $puntos = $cuenta->bono_login_cantidad;
                            $mensaje_primer_acceso .= 'de '.$puntos.' puntos -';
                            $concepto = 'Bono pimer ingreso';
                            $id_temporada = $cuenta->temporada_actual;
                            
                            $registro_puntos = PuntosExtra::where('id_cuenta', $cuenta->id)
                                                            ->where('id_temporada', $id_temporada )
                                                            ->where('id_usuario', $user->id )
                                                            ->where('concepto', $concepto )
                                                            ->first();
                            
                            if(!$registro_puntos){
                                $mensaje_primer_acceso .= 'No hay registro de puntos -';
                                $nuevo_registro = new PuntosExtra();
                                $nuevo_registro->id_cuenta = $cuenta->id;
                                $nuevo_registro->id_temporada = $id_temporada;
                                $nuevo_registro->id_usuario = $user->id;
                                $nuevo_registro->concepto = $concepto;
                                $nuevo_registro->puntos = $puntos;
                                $nuevo_registro->fecha_registro = date('Y-m-d H:i:s');

                                $nuevo_registro->save();
                                $primer_acceso = true;
                            }

                        }
                        break;
                    case '3':
                        $mensaje_primer_acceso .= 'Caso 3 -';
                        $cuenta = $pl_ni;
                        if($cuenta->bono_login=='si'){
                            $mensaje_primer_acceso .= 'Si hay bono activo -';
                            //Buscar si ya se registraron los puntos
                            $puntos = $cuenta->bono_login_cantidad;
                            $mensaje_primer_acceso .= 'de '.$puntos.' puntos -';
                            $concepto = 'Bono pimer ingreso';
                            $id_temporada = $cuenta->temporada_actual;
                            
                            $registro_puntos = PuntosExtra::where('id_cuenta', $cuenta->id)
                                                            ->where('id_temporada', $id_temporada )
                                                            ->where('id_usuario', $user->id )
                                                            ->where('concepto', $concepto )
                                                            ->first();
                            
                            if(!$registro_puntos){
                                $mensaje_primer_acceso .= 'No hay registro de puntos -';
                                $nuevo_registro = new PuntosExtra();
                                $nuevo_registro->id_cuenta = $cuenta->id;
                                $nuevo_registro->id_temporada = $id_temporada;
                                $nuevo_registro->id_usuario = $user->id;
                                $nuevo_registro->concepto = $concepto;
                                $nuevo_registro->puntos = $puntos;
                                $nuevo_registro->fecha_registro = date('Y-m-d H:i:s');

                                $nuevo_registro->save();
                                $primer_acceso = true;
                            }

                        }
                        break;
                    case '4':
                        $mensaje_primer_acceso .= 'Caso 4 -';
                        $cuenta = $pl_et;
                        if($cuenta->bono_login=='si'){
                            $mensaje_primer_acceso .= 'Si hay bono activo -';
                            //Buscar si ya se registraron los puntos
                            $puntos = $cuenta->bono_login_cantidad;
                            $mensaje_primer_acceso .= 'de '.$puntos.' puntos -';
                            $concepto = 'Bono pimer ingreso';
                            $id_temporada = $cuenta->temporada_actual;
                            
                            $registro_puntos = PuntosExtra::where('id_cuenta', $cuenta->id)
                                                            ->where('id_temporada', $id_temporada )
                                                            ->where('id_usuario', $user->id )
                                                            ->where('concepto', $concepto )
                                                            ->first();
                            
                            if(!$registro_puntos){
                                $mensaje_primer_acceso .= 'No hay registro de puntos -';
                                $nuevo_registro = new PuntosExtra();
                                $nuevo_registro->id_cuenta = $cuenta->id;
                                $nuevo_registro->id_temporada = $id_temporada;
                                $nuevo_registro->id_usuario = $user->id;
                                $nuevo_registro->concepto = $concepto;
                                $nuevo_registro->puntos = $puntos;
                                $nuevo_registro->fecha_registro = date('Y-m-d H:i:s');

                                $nuevo_registro->save();
                                $primer_acceso = true;
                            }

                        }
                        break;
                    case '5':
                        $mensaje_primer_acceso .= 'Caso 5 -';
                        $cuenta = $pl_test;
                        if($cuenta->bono_login=='si'){
                            $mensaje_primer_acceso .= 'Si hay bono activo -';
                            //Buscar si ya se registraron los puntos
                            $puntos = $cuenta->bono_login_cantidad;
                            $mensaje_primer_acceso .= 'de '.$puntos.' puntos -';
                            $concepto = 'Bono pimer ingreso';
                            $id_temporada = $cuenta->temporada_actual;
                            
                            $registro_puntos = PuntosExtra::where('id_cuenta', $cuenta->id)
                                                            ->where('id_temporada', $id_temporada )
                                                            ->where('id_usuario', $user->id )
                                                            ->where('concepto', $concepto )
                                                            ->first();
                            
                            if(!$registro_puntos){
                                $mensaje_primer_acceso .= 'No hay registro de puntos -';
                                $nuevo_registro = new PuntosExtra();
                                $nuevo_registro->id_cuenta = $cuenta->id;
                                $nuevo_registro->id_temporada = $id_temporada;
                                $nuevo_registro->id_usuario = $user->id;
                                $nuevo_registro->concepto = $concepto;
                                $nuevo_registro->puntos = $puntos;
                                $nuevo_registro->fecha_registro = date('Y-m-d H:i:s');

                                $nuevo_registro->save();
                                $primer_acceso = true;
                            }

                        }
                        break;
                    
                    
                    default:
                        $mensaje_primer_acceso .= 'Falso por default -';
                        $primer_acceso = false;
                        break;
                }
                
                return response()->json([
                    'message' => 'Hola '.$user->nombre,
                    'redireccion' => $redireccion,
                    'accessToken' => $token,
                    'token_type' => 'Bearer',
                    'user'=>$user,

                    'suscripcion_electrico'=>$suscripcion_el,
                    'distribuidor_electrico'=>$nombre_dist_el,
                    'region_electrico'=>$region_dist_el,

                    'suscripcion_ni'=>$suscripcion_ni,
                    'distribuidor_ni'=>$nombre_dist_ni,
                    'region_ni'=>$region_dist_ni,

                    'suscripcion_et'=>$suscripcion_et,
                    'distribuidor_et'=>$nombre_dist_et,
                    'region_et'=>$region_dist_et,

                    'suscripcion_test'=>$suscripcion_test,
                    'distribuidor_test'=>$nombre_dist_test,
                    'region_test'=>$region_dist_test,

                    'primer_acceso' => $primer_acceso,
                    'mensaje_primer_acceso' => $mensaje_primer_acceso
                ]);
            

        }else{

            $intento = new IntentoLogin();
            $intento->id_cuenta = $request->id_cuenta;
            
            switch ($request->id_cuenta) {
                case '1':
                    $intento->id_temporada = $pl_el->temporada_actual;
                    break;
                case '3':
                    $intento->id_temporada = $pl_ni->temporada_actual;
                    break;
                case '4':
                    $intento->id_temporada = $pl_et->temporada_actual;
                    break;
                
                default:
                    $intento->id_temporada = null;
                    break;
            }

            $intento->usuario = $request->Email;
            $user = User::where($loginType, $request->Email)->firstOrFail();
            $intento->try = $request->Password;
            if($user){
                $intento->id_usuario = $user->id;
            }else{
                $intento->id_usuario = null;
            }

            $intento->save();
            
            
            return response()->json(['message' => 'Las credenciales no coinciden'], 401);
        }
        
    }

    
}
