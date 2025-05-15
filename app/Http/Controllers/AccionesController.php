<?php

namespace App\Http\Controllers;

use App\Models\AccionesUsuarios;
use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Distribuidor;
use App\Models\DistribuidorSuscripciones;
use App\Models\SesionVis;
use App\Models\SesionEv;
use App\Models\Sesion;
use App\Models\EvaluacionPreg;
use App\Models\EvaluacionRes;
use App\Models\EvaluacionesRespuestas;
use App\Models\TriviaRes;
use App\Models\TriviaPreg;
use App\Models\Trivia;
use App\Models\JackpotIntentos;
use App\Models\Jackpot;
use App\Models\Cuenta;
use App\Models\Sku;
use App\Models\Logro;
use App\Models\IntentoLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Carbon\Carbon;


class AccionesController extends Controller
{
    //
    public function lista_acciones(Request $request)
    {
        // Capturar parámetros opcionales del request
        
        $correo = $request->input('correo_usuario');
        

        $usuario = User::where('email', $request->input('correo_usuario'))->first();

        

        if($usuario){
            $id_usuario = $usuario->id;
        }else{
            $id_usuario = null;
        }
        
        $id_cuenta = $request->input('id_cuenta');
        $id_temporada = $request->input('id_temporada');
        $cuentas = Cuenta::all();
        $temporadas = null;
        

        if($id_cuenta){
            $temporadas = Temporada::where('id_cuenta', $id_cuenta)->get();
        }

        if($id_usuario){
            $intentos_login = IntentoLogin::where('id_usuario', $id_usuario)->get();
        }else{
            $intentos_login = null;
        }

        // Consulta base
        $query = AccionesUsuarios::orderBy('id', 'desc');

        // Aplicar filtros opcionales si existen
        if ($id_usuario) {
            $query->where('id_usuario', $id_usuario);
        }

        if ($id_cuenta) {
            $query->where('id_cuenta', $id_cuenta);
        }

        if ($id_temporada) {
            $query->where('id_temporada', $id_temporada);
        }

        // Obtener los primeros 200 resultados
        $acciones = $query->take(200)->get();

        // Pasar variables necesarias a la vista
        return view('admin/acciones_lista', compact('acciones', 'id_usuario', 'id_cuenta', 'id_temporada', 'cuentas', 'temporadas', 'intentos_login'));
    }
}
