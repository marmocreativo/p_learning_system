<?php

namespace App\Http\Controllers;
use App\Models\Temporada;
use App\Models\Cuenta;
use App\Models\SesionEv;
use App\Models\SesionVis;
use App\Models\SesionDudas;
use App\Models\SesionAnexos;
use App\Models\EvaluacionPreg;
use App\Models\EvaluacionRes;
use App\Models\Trivia;
use App\Models\TriviaPreg;
use App\Models\TriviaRes;
use App\Models\TriviaGanador;
use App\Models\Jackpot;
use App\Models\JackpotPreg;
use App\Models\JackpotRes;
use App\Models\JackpotIntentos;
use App\Models\PuntosExtra;
use App\Models\Slider;
use App\Models\Publicacion;
use App\Models\Notificacion;
use App\Models\DistribuidoresSuscripciones;
use App\Models\Distribuidor;
use App\Models\UsuariosSuscripciones;
use App\Models\Logro;
use App\Models\LogroParticipacion;
use Illuminate\Support\Facades\DB;

use App\Exports\ReporteTemporadaExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class TemporadasController extends Controller
{
    //
    //
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $id_cuenta = $request->input('id_cuenta');
        $temporadas = Temporada::where('id_cuenta', $id_cuenta)->paginate();
        return view('admin/temporada_lista', compact('temporadas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin/temporada_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $temporada = new Temporada();

        $temporada->id_cuenta = $request->IdCuenta;
        $temporada->nombre = $request->Nombre;
        $temporada->descripcion = $request->Descripcion;
        $temporada->titulo_landing = $request->TituloLanding;
        $temporada->mensaje_landing = $request->MensajeLanding;
        $temporada->fecha_inicio = $request->FechaInicio;
        $temporada->fecha_final = $request->FechaFinal;


        $temporada->save();

        return redirect()->route('temporadas.show', $temporada->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $temporada = Temporada::find($id);
        $hoy = date('Y-m-d H:i:s');
        $sesiones_totales = SesionEv::where('id_temporada', $temporada->id)->count();
        $sesiones_publicadas = SesionEv::where('id_temporada', $temporada->id)->where('fecha_publicacion', '<=', $hoy)->count();
        $sesiones_pendientes = SesionEv::where('id_temporada', $temporada->id)->where('fecha_publicacion', '>', $hoy)->count();
        $trivias_totales = Trivia::where('id_temporada', $temporada->id)->count();
        $trivias_publicadas = Trivia::where('id_temporada', $temporada->id)->where('fecha_publicacion', '<=', $hoy)->count();
        $trivias_pendientes = Trivia::where('id_temporada', $temporada->id)->where('fecha_publicacion', '>', $hoy)->count();
        $trivia_activa = Trivia::where('id_temporada', $temporada->id)->where('estado', 'activo')->first();
        $jackpots_totales = Jackpot::where('id_temporada', $temporada->id)->count();
        $jackpot_activo = Jackpot::where('id_temporada', $temporada->id)->where('estado', 'activo')->first();
        $paginas_totales = Publicacion::where('id_temporada', $temporada->id)->where('clase', 'pagina')->count();
        $faq_totales = Publicacion::where('id_temporada', $temporada->id)->where('clase', 'faq')->count();
        $notificaciones_totales = Notificacion::where('id_temporada', $temporada->id)->count();
        $distribuidores_suscritos = DistribuidoresSuscripciones::where('id_temporada', $temporada->id)->count();
        $usuarios_suscritos = UsuariosSuscripciones::where('id_temporada', $temporada->id)->count();
        $logros_totales = Logro::where('id_temporada', $temporada->id)->count();
        $logros_participantes = LogroParticipacion::where('id_temporada', $temporada->id)->count();
        return view('admin/temporada_detalles', compact('temporada',
                                                        'sesiones_totales',
                                                        'sesiones_publicadas',
                                                        'sesiones_pendientes',
                                                        'trivias_totales',
                                                        'trivias_publicadas',
                                                        'trivias_pendientes',
                                                        'trivia_activa',
                                                        'jackpots_totales',
                                                        'jackpot_activo',
                                                        'paginas_totales',
                                                        'faq_totales',
                                                        'notificaciones_totales',
                                                        'distribuidores_suscritos',
                                                        'usuarios_suscritos',
                                                        'logros_totales',
                                                        'logros_participantes'
                                                    ));

    }

    public function reporte(Request $request, string $id)
    {
        //
        $temporada = Temporada::find($id);
        $hoy = date('Y-m-d H:i:s');
        $sesiones = SesionEv::where('id_temporada', $temporada->id)->get();
        $visualizaciones = SesionVis::where('id_temporada', $temporada->id)->get();
        $respuestas = EvaluacionRes::where('id_temporada', $temporada->id)->get();
        $trivias = Trivia::where('id_temporada', $temporada->id)->get();
        $trivias_respuestas = TriviaRes::where('id_temporada', $temporada->id)->get();
        $trivias_ganadores = TriviaGanador::where('id_temporada', $temporada->id)->get();
        $jackpots = Jackpot::where('id_temporada', $temporada->id)->get();
        $jackpots_intentos = JackpotIntentos::where('id_temporada', $temporada->id)->get();
        $puntos_extra = PuntosExtra::where('id_temporada', $temporada->id)->get();
        $region = $request->input('region');
        $distribuidor = $request->input('distribuidor');
        $distribuidores = Distribuidor::all();
        if($region!='todas'){
            $distribuidores = Distribuidor::where('region',$region)->get();
        }
        $usuarios_suscritos = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id)
            ->when($region !== 'todas', function ($query) use ($region) {
                return $query->where('distribuidores.region', $region);
            })
            // Añade la condición de distribuidor si no es 0
            ->when($distribuidor != 0, function ($query) use ($distribuidor) {
                return $query->where('distribuidores.id', $distribuidor);
            })
            ->select(
                'usuarios.id as id_usuario',
                'usuarios.nombre as nombre',
                'usuarios.apellidos as apellidos',
                'usuarios.email as email',
                'distribuidores.region as region',
                'distribuidores.nombre as distribuidor',
            )
            ->get();
            
        return view('admin/temporada_reporte', compact('temporada',
                                                        'sesiones',
                                                        'visualizaciones',
                                                        'respuestas',
                                                        'trivias',
                                                        'trivias_respuestas',
                                                        'trivias_ganadores',
                                                        'jackpots',
                                                        'jackpots_intentos',
                                                        'usuarios_suscritos',
                                                        'distribuidores',
                                                        'puntos_extra'
                                                    ));

    }

    /*
    public function reporte_excel (Request $request, string $id)
    {
        return Excel::download(new ReporteTemporadaExport($request, $id), 'reporte_temporada.xlsx');
        
        
    }
    */
    public function reporte_excel(Request $request, string $id)
    {
        // Crear una instancia del exportador con los parámetros requeridos
        $export = new ReporteTemporadaExport($request, $id);
        
        // Generar un nombre de archivo único usando timestamp
        $filename = 'reporte_temporada_' . time() . '.xlsx';

        // Generar la respuesta de descarga con los encabezados HTTP
        $response = Excel::download($export, $filename);

        // Establecer encabezados para desactivar la caché
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        // Retornar la respuesta al navegador
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $temporada = Temporada::find($id);
        return view('admin/temporada_form_actualizar', compact('temporada'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $temporada = Temporada::find($id);

        $temporada->id_cuenta = $request->IdCuenta;
        $temporada->nombre = $request->Nombre;
        $temporada->descripcion = $request->Descripcion;
        $temporada->titulo_landing = $request->TituloLanding;
        $temporada->mensaje_landing = $request->MensajeLanding;
        $temporada->fecha_inicio = $request->FechaInicio;
        $temporada->fecha_final = $request->FechaFinal;

        $temporada->save();

        return redirect()->route('temporadas.show', $temporada->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $temporada = Temporada::find($id);
        $temporada->delete();
        return redirect()->route('temporadas');
    }

    /* FUNCIONES API */
    public function show_api(Request $request)
    {
        //
        $cuenta = Cuenta::find($request->id);
        $temporada = Temporada::find($cuenta->temporada_actual);
        return response()->json($temporada);
        //return 'Hola';  
    }

    public function temporada_y_sesiones(Request $request)
    {

        $temporada = Temporada::find($request->input('id'));
        $sesiones = SesionEv::where('id_temporada', $temporada->id)->get();

        $completo = [
            'temporada' => $temporada,
            'sesiones' => $sesiones,
        ];
        return response()->json($completo);
    }
    public function lista_api(Request $request)
    {
        //
        $temporadas = Temporada::where('id_cuenta', $request->id_cuenta)->get();
        return response()->json($temporadas);
    }
}
