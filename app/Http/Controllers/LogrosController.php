<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Clase;
use App\Models\Temporada;
use App\Models\UsuariosSuscripciones;
use App\Models\Logro;
use App\Models\LogroParticipacion;
use App\Models\LogroAnexo;
use App\Models\User;
use App\Models\Distribuidor;
use Illuminate\Support\Facades\DB;

class LogrosController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //
        $id_temporada = $request->input('id_temporada');
        $logros = Logro::where('id_temporada', $id_temporada)->paginate();
        return view('admin/logro_lista', compact('logros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        return view('admin/logro_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         //
         $logro = new Logro();

         $logro->id_temporada = $request->IdTemporada;
         $logro->nombre = $request->Nombre;
         $logro->intrucciones = $request->Instrucciones;
         $logro->fecha_inicio = date('Y-m-d H:i:s', strtotime($request->FechaInicio.' '.$request->HoraInicio));
         $logro->fecha_vigente = date('Y-m-d H:i:s', strtotime($request->FechaVigente.' '.$request->HoraVigente));
 
         $logro->save();
 
         return redirect()->route('logro.show', $logro->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
        $logro = Logro::find($request->input('id_logro'));
        $participacion = LogroParticipacion::find($request->input('id_participacion'));
        $anexos = LogroAnexo::where('id_participacion',$request->input('id_participacion'))->get();
        return view('admin/logro_participacion_detalles', compact('logro', 'participantes'));
    }

    public function participacion(string $id)
    {
        //
        $logro = Logro::find($id);
        $preguntas = TriviaPreg::where('id_trivia',$id)->get();
        
        $respuestas = DB::table('trivias_respuestas')
            ->join('usuarios', 'trivias_respuestas.id_usuario', '=', 'usuarios.id')
            ->join('trivias_preguntas', 'trivias_respuestas.id_pregunta', '=', 'trivias_preguntas.id')
            ->where('trivias_respuestas.id_trivia', '=', $id)
            ->select('trivias_respuestas.id as id_respuesta','trivias_respuestas.respuesta_correcta as respuesta_resultado' , 'trivias_respuestas.*', 'usuarios.id as id_usuario', 'usuarios.*', 'trivias_preguntas.*')
            ->orderBy('trivias_respuestas.fecha_registro', 'desc')
            ->get();
        $ganadores = DB::table('trivias_ganadores')
            ->join('usuarios', 'trivias_ganadores.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'trivias_ganadores.id_distribuidor', '=', 'distribuidores.id')
            ->where('trivias_ganadores.id_trivia', '=', $id)
            ->select('trivias_ganadores.id as id_ganador', 'trivias_ganadores.*', 'usuarios.id as id_usuario', 'usuarios.nombre as nombre_usuario', 'usuarios.*', 'distribuidores.nombre as nombre_distribuidor', 'distribuidores.*')
            ->orderBy('trivias_ganadores.fecha_registro', 'desc')
            ->get();
        return view('admin/trivia_resultados', compact('trivia', 'preguntas', 'respuestas', 'ganadores'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $trivia = Trivia::find($id);
        return view('admin/trivia_form_actualizar', compact('trivia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $trivia = Trivia::find($id);

         $trivia->id_cuenta = $request->IdCuenta;
         $trivia->id_temporada = $request->IdTemporada;
         $trivia->titulo = $request->Titulo;
         $trivia->descripcion = $request->Descripcion;
         $trivia->mensaje_antes = $request->MensajeAntes;
         $trivia->mensaje_despues = $request->MensajeDespues;
         $trivia->fecha_publicacion = date('Y-m-d H:i:s', strtotime($request->FechaPublicacion.' '.$request->HoraPublicacion));
         $trivia->fecha_vigencia = date('Y-m-d H:i:s', strtotime($request->FechaVigencia.' '.$request->HoraVigencia));
         $trivia->puntaje = $request->Puntaje;
         $trivia->estado = $request->Estado;
 
         $trivia->save();
 
         return redirect()->route('trivias.show', $trivia->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $trivia = Trivia::findOrFail($id);
        $id_temporada = $trivia->id_temporada;
        // Buscar y eliminar registros relacionados en otras tablas
        TriviaPreg::where('id_trivia', $id)->delete();
        TriviaRes::where('id_trivia', $id)->delete();


        $trivia->delete();
        return redirect()->route('trivias', ['id_temporada'=>$id_temporada]);
    }
}
