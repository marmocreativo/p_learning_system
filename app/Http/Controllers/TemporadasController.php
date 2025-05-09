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
use App\Models\AccionesUsuarios;
use App\Models\Logro;
use App\Models\LogroParticipacion;
use App\Models\CanjeoCortes;
use App\Models\CanjeoCortesUsuarios;
use App\Models\CanjeoProductos;
use App\Models\CanjeoTransacciones;
use App\Models\CanjeoTransaccionesProductos;
use App\Models\top10Corte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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
        $cuenta = Cuenta::find($id_cuenta);
        $temporadas = Temporada::where('id_cuenta', $id_cuenta)->paginate();
        return view('admin/temporada_lista', compact('temporadas', 'cuenta'));
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
        $temporada->estado = $request->Estado;
        $temporada->url = $request->Url;


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
        $noticias_totales = Publicacion::where('id_temporada', $temporada->id)->where('clase', 'noticia')->count();
        $notificaciones_totales = Notificacion::where('id_temporada', $temporada->id)->count();
        $distribuidores_suscritos = DistribuidoresSuscripciones::where('id_temporada', $temporada->id)->count();
        $usuarios_suscritos = UsuariosSuscripciones::where('id_temporada', $temporada->id)->count();
        $logros_totales = Logro::where('id_temporada', $temporada->id)->count();
        $logros_participantes = LogroParticipacion::where('id_temporada', $temporada->id)->count();
        $productos = CanjeoProductos::where('id_temporada', $temporada->id)->count();
        $cortes = CanjeoCortesUsuarios::where('id_temporada', $temporada->id)->count();
        $transacciones = CanjeoTransacciones::where('id_temporada', $temporada->id)->count();
        $acciones = AccionesUsuarios::orderBy('id', 'desc')->take(200)->get();


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
                                                        'noticias_totales',
                                                        'notificaciones_totales',
                                                        'distribuidores_suscritos',
                                                        'usuarios_suscritos',
                                                        'logros_totales',
                                                        'logros_participantes',
                                                        'productos',
                                                        'cortes',
                                                        'transacciones',
                                                        'acciones'
                                                    ));

    }

    public function estadisticas(string $id)
{
    // Obtener temporada
    $temporada = Temporada::find($id);
    $fecha_inicio = Carbon::parse($temporada->fecha_inicio);
    $fecha_final = Carbon::parse($temporada->fecha_final);
    $id_temporada = $temporada->id;

    // Validación básica
    if (!$fecha_inicio || !$fecha_final || !$id_temporada) {
        return response()->json(['error' => 'Parámetros inválidos'], 400);
    }

    // Inicializar arrays para los datos de la gráfica
    $resultados = [];
    $fechas = [];
    $visualizaciones = [];
    $evaluaciones = [];
    $trivias = [];
    $jackpots = [];

    // Bucle para cada día entre las fechas
    for ($fecha = $fecha_inicio; $fecha->lte($fecha_final); $fecha->addDay()) {
        // Contar los registros que coincidan con la fecha y el id_temporada
        $vis = SesionVis::whereDate('fecha_ultimo_video', $fecha)
            ->where('id_temporada', $id_temporada)
            ->count();
        $respuestas = EvaluacionRes::whereDate('fecha_registro', $fecha)
            ->where('id_temporada', $id_temporada)
            ->count();
        $respuestas_trivia = TriviaRes::whereDate('fecha_registro', $fecha)
            ->where('id_temporada', $id_temporada)
            ->count();
        $jackpot = JackpotIntentos::whereDate('fecha_registro', $fecha)
            ->where('id_temporada', $id_temporada)
            ->count();

        // Agregar resultados al array
        $resultados[] = [
            'fecha' => $fecha->toDateString(),
            'visualizaciones' => $vis,
            'respuestas_evaluaciones' => $respuestas,
            'respuestas_trivias' => $respuestas_trivia,
            'intentos_jackpot' => $jackpot,
        ];

        // Llenar los arrays para las gráficas
        $fechas[] = $fecha->toDateString();
        $visualizaciones[] = $vis;
        $evaluaciones[] = $respuestas;
        $trivias[] = $respuestas_trivia;
        $jackpots[] = $jackpot;
    }

    // Pasar los datos a la vista
    return view('admin/temporada_estadisticas', compact('temporada', 'resultados', 'fechas', 'visualizaciones', 'evaluaciones', 'trivias', 'jackpots'));
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
            ->leftJoin('sucursales', 'usuarios_suscripciones.id_sucursal', '=', 'sucursales.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id)
            ->when($region !== 'todas', function ($query) use ($region) {
                return $query->where('distribuidores.region', $region);
            })
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
                'sucursales.nombre as sucursal',
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

    public function top_10_region(Request $request)
    {
        $cuenta = Cuenta::find($request->id);
        $temporada = Temporada::find($cuenta->temporada_actual);
        $unaSemanaAtras = Carbon::now()->subDay();
        $region = $request->input('region');
        $distribuidor = $request->input('distribuidor');

        // Buscar si existe un corte con menos de una semana de antigüedad
        $corte = top10Corte::where('temporada', $temporada->id)
            ->where('region', $region)
            ->where('created_at', '>=', $unaSemanaAtras)
            ->first();

        // Si no hay corte, calcular los puntajes y generar la lista
        if (!$corte) {
            $usuarios_suscritos = DB::table('usuarios_suscripciones')
                ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
                ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
                ->where('usuarios_suscripciones.id_temporada', '=', $temporada->id)
                ->when($region !== 'todas', function ($query) use ($region) {
                    return $query->where('usuarios_suscripciones.region', $region);
                })
                ->when($distribuidor != 0, function ($query) use ($distribuidor) {
                    return $query->where('distribuidores.id', $distribuidor);
                })
                ->select(
                    'usuarios.id as id_usuario',
                    'usuarios.nombre as nombre',
                    'usuarios.apellidos as apellidos',
                    'usuarios.email as email',
                    'usuarios.imagen as imagen',
                    'distribuidores.region as region',
                    'distribuidores.nombre as distribuidor',
                    'usuarios_suscripciones.id as id_suscripcion',
                    'usuarios_suscripciones.confirmacion_puntos as confirmacion_puntos',
                    'usuarios_suscripciones.premio as premio'
                )
                ->get();

            // Procesar la información de cada usuario para calcular puntajes
            $array_usuarios = [];
            $puntajes_completos = [];
            $ganadores_distribuidor = [];
            $ganadores_region = ['México' => 0, 'RoLA' => 0, 'Interna' => 0];

            // Inicializar contador de ganadores por distribuidor
            foreach (Distribuidor::all() as $distribuidor) {
                $ganadores_distribuidor[$distribuidor->nombre] = 0;
            }

            // Recorrer los usuarios suscritos y calcular puntajes
            foreach ($usuarios_suscritos as $usuario) {
                // Cálculo de puntajes basado en distintas métricas (sesiones, evaluaciones, trivias, etc.)
                $suma_visualizaciones = SesionVis::where('id_usuario', $usuario->id_usuario)
                    ->where('id_temporada', $temporada->id)->sum('puntaje');
                $cantidad_visualizaciones = SesionVis::where('id_usuario', $usuario->id_usuario)
                    ->where('id_temporada', $temporada->id)->distinct('id_sesion')->count();

                $suma_evaluaciones = EvaluacionRes::where('id_usuario', $usuario->id_usuario)
                    ->where('id_temporada', $temporada->id)->sum('puntaje');
                $cantidad_evaluaciones = EvaluacionRes::where('id_usuario', $usuario->id_usuario)
                    ->where('id_temporada', $temporada->id)->distinct('id_sesion')->count();

                $suma_trivias = TriviaRes::where('id_usuario', $usuario->id_usuario)
                    ->where('id_temporada', $temporada->id)->sum('puntaje');
                $cantidad_trivias = TriviaRes::where('id_usuario', $usuario->id_usuario)
                    ->where('id_temporada', $temporada->id)->count();

                $suma_jackpots = JackpotIntentos::where('id_usuario', $usuario->id_usuario)
                    ->where('id_temporada', $temporada->id)->sum('puntaje');

                $suma_extras = PuntosExtra::where('id_usuario', $usuario->id_usuario)
                    ->where('id_temporada', $temporada->id)->sum('puntos');

                $puntaje_total = $suma_visualizaciones + $suma_evaluaciones + $suma_trivias + $suma_jackpots + $suma_extras;
                $puntaje_oculto = $cantidad_visualizaciones + $cantidad_evaluaciones + $cantidad_trivias;

                if (!in_array($puntaje_total, $puntajes_completos)) {
                    $puntajes_completos[] = $puntaje_total;
                }

                // Agregar usuario al array con su información y puntajes
                $array_usuarios[] = [
                    'id' => $usuario->id_usuario,
                    'nombre' => $usuario->nombre . ' ' . $usuario->apellidos,
                    'email' => $usuario->email,
                    'imagen' => $usuario->imagen,
                    'suscripcion' => $usuario->id_suscripcion,
                    'distribuidor' => $usuario->distribuidor,
                    'region' => $usuario->region,
                    'puntaje' => $puntaje_total,
                    'puntaje_oculto' => $puntaje_oculto,
                    'premio' => $usuario->premio
                ];

                // Verificación de premios
                if ($usuario->premio == 'experiencia') {
                    $ganadores_region[$usuario->region]++;
                } elseif ($usuario->premio == 'bono') {
                    $ganadores_distribuidor[$usuario->distribuidor]++;
                }
            }

            // Obtener los top 10 puntajes
            rsort($puntajes_completos);
            $puntajes_top = array_slice($puntajes_completos, 0, 10);

            // Filtrar los usuarios con los puntajes top
            $usuarios_filtrados = array_filter($array_usuarios, function($usuario) use ($puntajes_top) {
                return in_array($usuario['puntaje'], $puntajes_top);
            });

            // Ordenar usuarios filtrados por puntaje
            usort($usuarios_filtrados, function($a, $b) {
                if ($a['puntaje'] === $b['puntaje']) {
                    return $b['puntaje_oculto'] <=> $a['puntaje_oculto'];
                }
                return $b['puntaje'] <=> $a['puntaje'];
            });

            // Guardar el nuevo corte en la base de datos
            $guardar_corte = new top10Corte();
            $guardar_corte->cuenta = $cuenta->id;
            $guardar_corte->temporada = $temporada->id;
            $guardar_corte->nombre_corte = 'Corte ' . $region . ' ' . date('Y-m-d');
            $guardar_corte->region = $region;
            $guardar_corte->lista = json_encode([  // Guardar lista como JSON
                'puntajes_top' => $puntajes_top,
                'usuarios' => $usuarios_filtrados,
                'ganadores_region' => $ganadores_region,
                'ganadores_distribuidor' => $ganadores_distribuidor
            ]);
            $guardar_corte->save();

            // Devolver la vista con los datos calculados
            return view('admin/top_10_region', compact('temporada', 'puntajes_top', 'usuarios_filtrados', 'ganadores_region', 'ganadores_distribuidor'));
        } else {
            // Si hay un corte existente, usar los datos almacenados
            $lista = json_decode($corte->lista, true);  // Decodificar JSON
            $puntajes_top = $lista['puntajes_top'];
            $usuarios_filtrados = $lista['usuarios'];
            $ganadores_region = $lista['ganadores_region'];
            $ganadores_distribuidor = $lista['ganadores_distribuidor'];

            // Devolver la vista con los datos del corte existente
            return view('admin/top_10_region', compact('temporada', 'puntajes_top', 'usuarios_filtrados', 'ganadores_region', 'ganadores_distribuidor'));
        }
    }

    public function top_10_borrar_corte(Request $request)
    {
        DB::table('top_10_cortes')->truncate();
        return redirect()->route('top_10_region', ['id'=>$request->id, 'region'=>$request->region]);
    }


    public function actualizar_premio_top_10(Request $request){
        $suscripcion = UsuariosSuscripciones::find($request->input('id_suscripcion'));
        if(!empty($request->input('premio'))){
            $suscripcion->premio = $request->input('premio');
            $suscripcion->save();
        }
        
        $id_cuenta = $request->input('cuenta');
        $region = $request->input('region');

        return redirect()->route('top_10_region', ['id'=>$id_cuenta, 'region'=>$region]);
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

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);
    
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'calendario_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $temporada->imagen;
        }

        $temporada->id_cuenta = $request->IdCuenta;
        $temporada->nombre = $request->Nombre;
        $temporada->descripcion = $request->Descripcion;
        $temporada->titulo_landing = $request->TituloLanding;
        $temporada->mensaje_landing = $request->MensajeLanding;
        $temporada->fecha_inicio = $request->FechaInicio;
        $temporada->fecha_final = $request->FechaFinal;
        $temporada->estado = $request->Estado;
        $temporada->imagen = $nombreImagen;
        $temporada->url = $request->Url;

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

    public function temporada_y_sesiones_2025(Request $request)
    {
        $id_cuenta = $request->input('cuenta');
        $temporada = Temporada::where('id_cuenta', $id_cuenta)->where('url', $request->input('url'))->first();
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
    
    public function top_10_region_api(Request $request)
    {
        //
        $cuenta = Cuenta::find($request->id);
        $temporada = Temporada::find($cuenta->temporada_actual);
        $hoy = date('Y-m-d H:i:s');
        $unaSemanaAtras = Carbon::now()->subWeek();
        $region = $request->input('region');
        $corte = top10Corte::where('temporada', $temporada->id)
            ->where('region', $region)
            ->where('created_at', '>=', $unaSemanaAtras)
            ->first();
        $fecha_corte = '';
        if(!$corte){
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
                ->where('usuarios_suscripciones.id_temporada', '=', $temporada->id)
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
                    'usuarios.imagen as imagen',
                    'distribuidores.region as region',
                    'distribuidores.nombre as distribuidor',
                    'usuarios_suscripciones.id as id_suscripcion',
                    'usuarios_suscripciones.premio as premio'
                )
                ->get();
            $array_usuarios = array();
            $puntajes_completos = array();
            $puntajes_top = array();
            $i=1;
            foreach($usuarios_suscritos as $usuario){
                // visualizaciones
                // Cuento las visualizaciones
                //$puntos_visualizaciones = SesionVis::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$temporada->id)->pluck('puntaje')->sum();
                $visualizaciones = SesionVis::where('id_usuario', $usuario->id_usuario)->where('id_temporada', $temporada->id)->get();
                $suma_visualizaciones = $visualizaciones->sum('puntaje');
                $cantidad_visualizaciones = $visualizaciones->pluck('id_sesion')->unique()->count();
                $cantidad_visualizaciones = $cantidad_visualizaciones*1;
                // Evaluaciones
                $evaluaciones = EvaluacionRes::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$temporada->id)->get();
                $suma_evaluaciones = $visualizaciones->sum('puntaje');
                $cantidad_evaluaciones = $visualizaciones->pluck('id_sesion')->unique()->count();
                $cantidad_evaluaciones = $cantidad_evaluaciones*2;
                // trivias
                $trivias = TriviaRes::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$temporada->id)->get();
                $suma_trivias = $trivias->sum('puntaje');
                $cantidad_trivias = $trivias->pluck('id_sesion')->unique()->count();
                $cantidad_trivias = $cantidad_trivias*3;
                // Jackpot
                $jackpots = JackpotIntentos::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$temporada->id)->get();
                $suma_jackpots = $jackpots->sum('puntaje');
                $cantidad_jackpots = $jackpots->pluck('id_sesion')->unique()->count();
                $cantidad_jackpots = $cantidad_jackpots*0;
                // Puntos extra
                $puntos_extra = PuntosExtra::where('id_usuario',$usuario->id_usuario)->where('id_temporada',$temporada->id)->get();
                $suma_extras = $puntos_extra->sum('puntos');
                $cantidad_extras = $puntos_extra->pluck('id_sesion')->unique()->count();
                $cantidad_extras = $cantidad_extras*-1;

                $puntaje_total = $suma_visualizaciones+$suma_evaluaciones+$suma_trivias+$suma_jackpots+$suma_extras;
                $puntaje_oculto = $cantidad_visualizaciones+$cantidad_evaluaciones+$cantidad_trivias+$cantidad_jackpots+$cantidad_extras;

                if (!in_array($puntaje_total, $puntajes_completos)) {
                    $puntajes_completos[] = $puntaje_total;
                }

                $array_usuarios[] = [
                    'id'=>  $usuario->id_usuario,
                    'nombre'=>  $usuario->nombre.' '.$usuario->apellidos,
                    'email'=>  $usuario->email,
                    'imagen'=>  $usuario->imagen,
                    'suscripcion'=>  $usuario->id_suscripcion,
                    'distribuidor'=>  $usuario->distribuidor,
                    'region'=>  $usuario->region,
                    'puntaje'=> $puntaje_total,
                    'puntaje_oculto'=> $puntaje_oculto,
                    'premio'=>  $usuario->premio,
                ];
                $i++;
            }
            // obtengo los puntajes_top 
            rsort($puntajes_completos);
            $puntajes_top = array_slice($puntajes_completos, 0, 10);

            // Filtro los usuarios
            $usuarios_filtrados = array_filter($array_usuarios, function($usuario) use ($puntajes_top) {
                return in_array($usuario['puntaje'], $puntajes_top);
            });

            usort($usuarios_filtrados, function($a, $b) {
                if ($a['puntaje'] === $b['puntaje']) {
                    // Si los puntajes son iguales, comparar por puntaje oculto
                    return $b['puntaje_oculto'] <=> $a['puntaje_oculto'];
                }
                // Si los puntajes son diferentes, comparar por puntaje
                return $b['puntaje'] <=> $a['puntaje'];
            });

            $fecha_corte = date('d/m/Y');
            $completo = [
                'puntajes_top' => $puntajes_top,
                'usuarios' => $usuarios_filtrados,
                'fecha_corte' => $fecha_corte
            ];
            return response()->json($completo);
        }else{
            // Si hay un corte existente, usar los datos almacenados
            $lista = json_decode($corte->lista, true);  // Decodificar JSON
            $puntajes_top = $lista['puntajes_top'];
            $usuarios_filtrados = $lista['usuarios'];
            $ganadores_region = $lista['ganadores_region'];
            $ganadores_distribuidor = $lista['ganadores_distribuidor'];
            $completo = [
                'puntajes_top' => $puntajes_top,
                'usuarios' => $usuarios_filtrados,
                'fecha_corte' => $corte->created_at
            ];
            return response()->json($completo);
        }
    }
}
