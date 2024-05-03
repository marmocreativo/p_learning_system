<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Distribuidor;
use App\Models\DistribuidorSuscripciones;
use App\Models\SesionVis;
use App\Models\SesionEv;
use App\Models\EvaluacionesRespuestas;
use App\Models\TriviaRes;
use App\Models\Trivia;
use App\Models\JackpotIntentos;
use App\Models\Jackpot;
use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class FrontController extends Controller
{

    public function index()
    {
        //
        return view('front/home');
    }

    public function scripts_ajustes()
    {
        //
        echo 'No hay ajustes por ejecutar';
        // Ejecutar la migración
        
        Artisan::call('migrate');

        // Obtener el resultado de la ejecución de la migración
        $output = Artisan::output();

        // Puedes hacer algo con la salida (por ejemplo, devolverla como respuesta)
        return response()->json(['message' => 'Migraciones ejecutadas', 'output' => $output]);
        
    }
}
