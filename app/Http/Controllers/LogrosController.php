<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Cuenta;
use App\Models\Clase;
use App\Models\Temporada;
use App\Models\UsuariosSuscripciones;
use App\Models\Logro;
use App\Models\Sku;
use App\Models\LogroParticipacion;
use App\Models\LogroAnexo;
use App\Models\LogroAnexoProducto;
use App\Models\User;
use App\Models\AccionesUsuarios;
use App\Models\Distribuidor;
use App\Models\Sesion;
use App\Models\SesionVis;
use Illuminate\Support\Facades\DB;


use App\Exports\LogrosExport;

use App\Mail\ConfirmacionNivelChampions;
use App\Mail\FinalizacionChampions;
use App\Mail\DesafioChampions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


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
        $temporada = Temporada::find($id_temporada);
        $cuenta = Cuenta::where('id', $temporada->id_cuenta)->first();
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;

        // Obtener todos los distribuidores
        $distribuidores = Distribuidor::all();

        // Inicializar arreglo de logros por distribuidor
        $logrosPorDistribuidor = [];

        // Bucle por cada distribuidor
        foreach ($distribuidores as $distribuidor) {
            $logros = Logro::where('id_temporada', $id_temporada)
                ->where('id_distribuidor', $distribuidor->id)
                ->orderBy('orden', 'asc')
                ->get();

            // Asignar los logros al array, usando el ID como clave
            $logrosPorDistribuidor[$distribuidor->id] = $logros;
            }

            // También puedes obtener los logros sin distribuidor (base)
            $logrosSinDistribuidor = Logro::where('id_temporada', $id_temporada)
                ->where(function($query) {
                    $query->whereNull('id_distribuidor')
                        ->orWhere('id_distribuidor', '');
                })
                ->orderBy('orden', 'asc')
                ->get();

            // Enviar todo a la vista
            return view('admin/logro_lista', compact('distribuidores', 'logrosPorDistribuidor', 'logrosSinDistribuidor', 'temporada', 'cuenta', 'cuentas', 'color_barra_superior', 'logo_cuenta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $temporada = Temporada::find($request->input('id_temporada'));
        $distribuidores = Distribuidor::all();
        return view('admin/logro_form', compact('temporada', 'distribuidores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         //
         $logro = new Logro();

         // Validar la solicitud
       $request->validate([
        'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'logro_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = 'default.jpg';
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_logro_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = 'default_fondo.jpg';
        }

         $logro->id_temporada = $request->IdTemporada;
         $logro->nombre = $request->Nombre;
         $logro->instrucciones = $request->Instrucciones;
         $logro->contenido = $request->Contenido;
         $logro->sesiones = $request->Sesiones;
         $logro->premio = $request->Premio;
         $logro->premio_rola = $request->PremioRola;
         $logro->nivel_a = $request->NivelA;
         $logro->nivel_b = $request->NivelB;
         $logro->nivel_c = $request->NivelC;
         $logro->nivel_especial = $request->NivelEspecial;
         $logro->premio_a = $request->PremioA;
         $logro->premio_b = $request->PremioB;
         $logro->premio_c = $request->PremioC;
         $logro->premio_especial = $request->PremioEspecial;
         $logro->cantidad_evidencias = $request->CantidadEvidencias;
         $logro->nivel_usuario = $request->NivelUsuario;
         $logro->region = $request->Region;
         $logro->imagen = $nombreImagen;
         $logro->imagen_fondo = $nombreImagenFondo;
         $logro->id_distribuidor = $request->IdDistribuidor;
         $logro->fecha_inicio = date('Y-m-d H:i:s', strtotime($request->FechaInicio.' '.$request->HoraInicio));
         $logro->fecha_vigente = date('Y-m-d H:i:s', strtotime($request->FechaVigente.' '.$request->HoraVigente));
         $logro->orden = $request->Orden;
 
         $logro->save();
 
         return redirect()->route('logros.show', $logro->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $logro = Logro::with([
            'participaciones.usuario',
            'participaciones.distribuidor',
            'participaciones.anexosNoValidados',
        ])->findOrFail($id);

        // Obtener el valor de búsqueda si existe
        $busqueda = $request->input('busqueda');

        // Filtrar los SKUs si hay búsqueda
        $skusQuery = Sku::where('desafio', $logro->nombre);

        if ($busqueda) {
            $skusQuery->where('sku', 'like', '%' . $busqueda . '%');
        }

        $skus = $skusQuery->get();

        $temporada = Temporada::find($logro->id_temporada);
        $cuenta = Cuenta::find($temporada->id_cuenta);
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/' . $cuenta->logotipo;

        return view('admin/logro_detalles', compact(
            'logro', 'skus', 'cuenta', 'temporada', 'cuentas', 'color_barra_superior', 'logo_cuenta'
        ));
    }

    public function participacion(Request $request)
    {
        //
        $logro = Logro::find($request->input('id_logro'));
        $participacion = LogroParticipacion::find($request->input('id_participacion'));
        $anexos = LogroAnexo::where('id_participacion',$request->input('id_participacion'))->get();
        
        
        return view('admin/logro_participacion', compact('logro', 'participacion', 'anexos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $logro = Logro::find($id);
        $distribuidores = Distribuidor::all();
        return view('admin/logro_form_actualizar', compact('logro', 'distribuidores'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $logro = Logro::find($id);

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'TablaMx' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            'TablaRola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'logro'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $logro->imagen;
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_logro_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = $logro->imagen_fondo;
        }

        if ($request->hasFile('TablaMx')) {
            $tabla_mx = $request->file('TablaMx');
            $nombreTablaMx = 'tabla_mx_'.time().'.'.$tabla_mx->extension();
            $tabla_mx->move(base_path('../public_html/img/publicaciones'), $nombreTablaMx);
        }else{
            $nombreTablaMx = $logro->tabla_mx;
        }
        if ($request->hasFile('TablaRola')) {
            $tabla_rola = $request->file('TablaRola');
            $nombreTablaRola = 'tabla_rola_'.time().'.'.$tabla_rola->extension();
            $tabla_rola->move(base_path('../public_html/img/publicaciones'), $nombreTablaRola);
        }else{
            $nombreTablaRola = $logro->tabla_rola;
        }

        $logro->id_temporada = $request->IdTemporada;
        $logro->nombre = $request->Nombre;
        $logro->instrucciones = $request->Instrucciones;
        $logro->premio = $request->Premio;
        $logro->premio_rola = $request->PremioRola;
        $logro->contenido = $request->Contenido;
        $logro->sesiones = $request->Sesiones;
        $logro->nivel_a = $request->NivelA;
        $logro->nivel_b = $request->NivelB;
        $logro->nivel_c = $request->NivelC;
        $logro->nivel_especial = $request->NivelEspecial;
        $logro->nivel_usuario = $request->NivelUsuario;
        $logro->region = $request->Region;
        $logro->premio_a = $request->PremioA;
         $logro->premio_b = $request->PremioB;
         $logro->premio_c = $request->PremioC;
         $logro->premio_especial = $request->PremioEspecial;

         $logro->premio_rola_a = $request->PremioRolaA;
         $logro->premio_rola_b = $request->PremioRolaB;
         $logro->premio_rola_c = $request->PremioRolaC;
         $logro->premio_rola_especial = $request->PremioRolaEspecial;

         $logro->cantidad_evidencias = $request->CantidadEvidencias;
        $logro->imagen = $nombreImagen;
        $logro->imagen_fondo = $nombreImagenFondo;
        $logro->tabla_mx = $nombreTablaMx;
        $logro->tabla_rola = $nombreTablaRola;
         $logro->id_distribuidor = $request->IdDistribuidor;
        $logro->fecha_inicio = date('Y-m-d H:i:s', strtotime($request->FechaInicio.' '.$request->HoraInicio));
        $logro->fecha_vigente = date('Y-m-d H:i:s', strtotime($request->FechaVigente.' '.$request->HoraVigente));
        $logro->orden = $request->Orden;
 
         $logro->save();

         Sku::where('id_logro', $logro->id)->update(['desafio' => $logro->nombre]);
 
         return redirect()->route('logros.show', $logro->id);
    }

    public function actualizar_anexo(Request $request, string $id)
    {
        //
        $anexo = LogroAnexo::find($id);
        $anexo->nivel = $request->Nivel;
        $anexo->validado = $request->Validado;
        $anexo->comentario = $request->Comentario;
        $anexo->save();
 
         return redirect()->route('logros.detalles_participacion', ['id'=>$anexo->id_participacion]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $logro = Logro::find($id);
        $id_temporada = $logro->id_temporada;
        // Buscar y eliminar registros relacionados en otras tablas
        LogroAnexo::where('id_logro', $id)->delete();
        LogroParticipacion::where('id_logro', $id)->delete();


        $logro->delete();
        return redirect()->route('logros', ['id_temporada'=>$id_temporada]);
    }

    public function destroy_participacion(string $id)
    {
        //
        $participacion = LogroParticipacion::find($id);
        $id_temporada = $participacion->id_temporada;
        // Buscar y eliminar registros relacionados en otras tablas
        
        LogroAnexo::where('id_participacion', $participacion->$id)->delete();
        LogroAnexoProducto::where('id_participacion', $participacion->$id)->delete();


        $participacion->delete();
        return redirect()->route('logros.show', $participacion->id_logro);
        
    }

    public function destroy_anexo(string $id)
    {
        //
        $anexo = LogroAnexo::find($id);
        $id_temporada = $anexo->id_temporada;
        $id_participacion = $anexo->id_participacion;

        // Buscar y eliminar registros relacionados en otras tablas
        
        LogroAnexo::where('id', $anexo->$id)->delete();


        $anexo->delete();
        return redirect()->route('logros.detalles_participacion', ['id'=>$id_participacion]);
        
    }

    public function detalles_participacion(Request $request)
    {
        //
        $participacion = LogroParticipacion::find($request->input('id'));
        $logro = Logro::find($participacion->id_logro);
        $usuario = User::find($participacion->id_usuario);
        $id_temporada = $participacion->id_temporada;
        // Buscar y eliminar registros relacionados en otras tablas
        
        $anexos = LogroAnexo::where('id_participacion', $request->input('id'))->get();

        return view('admin/logro_detalles_participacion', compact('participacion', 'usuario', 'logro', 'anexos'));
        
    }


    public function participacion_update(Request $request, string $id)
{
    try {
        // Obtenemos y actualizamos la participación
        $participacion = LogroParticipacion::find($id);
        $logro = Logro::find($participacion->id_logro);
        $nivel_email = '';
        
        switch ($request->ConfirmacionNivel) {
            case 'a':
                $participacion->confirmacion_nivel_a = 'si';
                $participacion->confirmacion_nivel_b = 'no';
                $participacion->confirmacion_nivel_c = 'no';
                $participacion->confirmacion_nivel_especial = 'no';
                $nivel_email = 'A';
                break;
            case 'b':
                $participacion->confirmacion_nivel_a = 'si';
                $participacion->confirmacion_nivel_b = 'si';
                $participacion->confirmacion_nivel_c = 'no';
                $participacion->confirmacion_nivel_especial = 'no';
                $nivel_email = 'B';
                break;
            case 'c':
                $participacion->confirmacion_nivel_a = 'si';
                $participacion->confirmacion_nivel_b = 'si';
                $participacion->confirmacion_nivel_c = 'si';
                $participacion->confirmacion_nivel_especial = 'no';
                $nivel_email = 'C';
                break;
            case 'especial':
                $participacion->confirmacion_nivel_a = 'si';
                $participacion->confirmacion_nivel_b = 'si';
                $participacion->confirmacion_nivel_c = 'si';
                $participacion->confirmacion_nivel_especial = 'si';
                $nivel_email = 'Especial';
                break;
            default:
                # code...
                break;
        }
        
        $participacion->estado = $request->Estado;
        
        // Primero guardamos los cambios en la base de datos
        $participacion->save();
        
        // Luego intentamos enviar el correo, pero manejamos posibles errores
        try {
            if($participacion->estado=='finalizado'){
                $data = [
                    'titulo' => ' ¡Desafío completado! ',
                    'contenido' => '<p>"Has superado los niveles de tu desafio Champions  ¡Gracias por participar, y prepárate para la próxima temporada!</p>',
                    'boton_texto' => 'Desafío Champions',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
                ];
                Mail::to($request->UsuarioEmail)->send(new FinalizacionChampions($data));
            } else {
                $data = [
                    'desafio' => $logro->nombre,
                    'nivel' => $nivel_email,
                    'estado' => $logro->estado,
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
                ];
                Mail::to($request->UsuarioEmail)->send(new ConfirmacionNivelChampions($data));
            }
        } catch (\Exception $e) {
            // Registramos el error pero continuamos con la ejecución
            \Log::error('Error al enviar correo de actualización de participación: ' . $e->getMessage());
        }
        
        return redirect()->route('logros.detalles_participacion', ['id'=>$participacion->id]);
        
    } catch (\Exception $e) {
        \Log::error('Error en participacion_update: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Ha ocurrido un error al actualizar la participación.']);
    }
}
public function reporte_excel(Request $request)
{   
    /*
    // Validar los parámetros requeridos
    $request->validate([
        'id_temporada' => 'required|integer',
        'id_logro' => 'required|integer',
    ]);

    // Nombre del archivo que se va a descargar
    $nombreArchivo = 'logros_export_' . now()->format('Ymd_His') . '.xlsx';

    // Retornar la descarga
    return Excel::download(new LogrosExport($request), $nombreArchivo);
    */
    return 'Este es el reporte';
}

    public function lista_logros_api(Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
       
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
        switch ($distribuidor->region) {
            case 'RoLA':
                $logros = Logro::where('id_temporada', $id_temporada)
                        ->where(function($query) use ($distribuidor) {
                            $query->where('region', 'RoLA')
                                ->orWhere('region', 'Todas');
                        })
                        ->where(function($query) {
                            $query->whereNull('id_distribuidor')
                                ->orWhere('id_distribuidor', '');
                        })
                        ->orderBy('orden', 'asc')
                        ->get();
                break;
            
            default:
                $logros = Logro::where('id_temporada', $id_temporada)
                ->where(function($query) use ($distribuidor) {
                    $query->where('region', 'México')
                        ->orWhere('region', 'Interna')
                        ->orWhere('region', 'Todas');
                })
                ->where(function($query) {
                    $query->whereNull('id_distribuidor')
                        ->orWhere('id_distribuidor', '');
                })
                ->orderBy('orden', 'asc')
                ->get();
                break;
        }
        
        
        $participaciones = DB::table('logros_participantes')
            ->join('logros', 'logros_participantes.id_logro', '=', 'logros.id')
            ->where('logros_participantes.id_usuario', '=', $id_usuario)
            ->where('logros_participantes.id_temporada', $id_temporada)
            ->select('logros_participantes.*', 'logros.*')
            ->get();
        $premios_acumulados = 0;
        foreach($participaciones as $participacion){
            if($participacion->estado=='finalizado'){
                $log = Logro::where('id', $participacion->id_logro)->first();
                if($participacion->confirmacion_nivel_especial = 'si'){  $premios_acumulados += $log->premio_especial; }
                if($participacion->confirmacion_nivel_c = 'si'){ $premios_acumulados += $log->premio_c; }
                if($participacion->confirmacion_nivel_b = 'si'){ $premios_acumulados += $log->premio_b; }
                if($participacion->confirmacion_nivel_a = 'si'){ $premios_acumulados += $log->premio_a; }
            }
        }
         
        $completo = [
            'logros' => $logros,
            'participaciones' => $participaciones,
            'premios_acumulados' => $premios_acumulados,
            'nivel_usuario' => $suscripcion->nivel_usuario,
            'champions_a' => $suscripcion->champions_a,
            'champions_b' => $suscripcion->champions_b,
        ];
        return response()->json($completo);
    }

     public function lista_logros_2025_api(Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
       
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $distribuidor = null;
        if ($suscripcion && $suscripcion->id_distribuidor) {
            $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
        }
        switch ($distribuidor->region) {
            case 'RoLA':
                $logros = Logro::where('id_temporada', $id_temporada)
                        ->where(function($query) use ($distribuidor) {
                            $query->where('region', 'RoLA')
                                ->orWhere('region', 'Todas');
                        })
                        ->where(function($query) {
                            $query->whereNull('id_distribuidor')
                                ->orWhere('id_distribuidor', '');
                        })
                        ->orderBy('orden', 'asc')
                        ->get();
                

                $logros_distribuidor = collect(); // colección vacía por defecto

                if ($distribuidor) {
                    $logros_distribuidor = Logro::where('id_temporada', $id_temporada)
                        ->where(function($query) use ($distribuidor) {
                            $query->where('region', 'RoLA')
                                ->orWhere('region', 'Todas');
                        })
                        ->where(function($query) use ($distribuidor) {
                            $query->where('id_distribuidor', $distribuidor->id);
                        })
                        ->orderBy('orden', 'asc')
                        ->get();
                }
                 break;

            
            default:
                $logros = Logro::where('id_temporada', $id_temporada)
                ->where(function($query) use ($distribuidor) {
                    $query->where('region', 'México')
                        ->orWhere('region', 'Interna')
                        ->orWhere('region', 'Todas');
                })
                ->where(function($query) use ($distribuidor) {
                    $query->whereNull('id_distribuidor')
                        ->orWhere('id_distribuidor', '');
                })
                ->orderBy('orden', 'asc')
                ->get();

                $logros_distribuidor = collect(); // colección vacía por defecto

                if ($distribuidor) {
                    $logros_distribuidor = Logro::where('id_temporada', $id_temporada)
                        ->where(function($query) {
                            $query->where('region', 'México')
                                ->orWhere('region', 'Interna')
                                ->orWhere('region', 'Todas');
                        })
                        ->where(function($query) use ($distribuidor) {
                            $query->where('id_distribuidor', $distribuidor->id);
                        })
                        ->orderBy('orden', 'asc')
                        ->get();
                }

                break;
        }
        
        
        $participaciones = DB::table('logros_participantes')
            ->join('logros', 'logros_participantes.id_logro', '=', 'logros.id')
            ->where('logros_participantes.id_usuario', '=', $id_usuario)
            ->where('logros_participantes.id_temporada', $id_temporada)
            ->select('logros_participantes.*', 'logros.*')
            ->get();
        $premios_acumulados = 0;
        foreach($participaciones as $participacion){
                $log = Logro::where('id', $participacion->id_logro)->first();
                
                if($distribuidor->region=='RoLA'){
                    if($participacion->confirmacion_nivel_especial == 'si'){  $premios_acumulados += $log->premio_rola_especial; }
                    if($participacion->confirmacion_nivel_c == 'si'){ $premios_acumulados += $log->premio_rola_c; }
                    if($participacion->confirmacion_nivel_b == 'si'){ $premios_acumulados += $log->premio_rola_b; }
                    if($participacion->confirmacion_nivel_a == 'si'){ $premios_acumulados += $log->premio_rola_a; }
                }else{
                    if($participacion->confirmacion_nivel_especial == 'si'){  $premios_acumulados += $log->premio_especial; }
                    if($participacion->confirmacion_nivel_c == 'si'){ $premios_acumulados += $log->premio_c; }
                    if($participacion->confirmacion_nivel_b == 'si'){ $premios_acumulados += $log->premio_b; }
                    if($participacion->confirmacion_nivel_a == 'si'){ $premios_acumulados += $log->premio_a; }
                }
                
        }
         
        $completo = [
            'logros' => $logros,
            'logros_distribuidor' => $logros_distribuidor,
            'participaciones' => $participaciones,
            'premios_acumulados' => $premios_acumulados,
            'nivel_usuario' => $suscripcion->nivel_usuario,
            'champions_a' => $suscripcion->champions_a,
            'champions_b' => $suscripcion->champions_b,
        ];
        return response()->json($completo);
    }
    public function detalles_logro_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
        $logro = Logro::find($request->input('id'));
        $participacion = LogroParticipacion::where('id_logro', $logro->id)->where('id_usuario', $id_usuario)->first();

        //$participaciones = LogroParticipacion::where('id_logro', $id_usuario)->get();
        if($participacion){

            $participaciones = LogroAnexo::where('id_participacion', $participacion->id)
                ->with('productos') // Carga los productos asociados a cada evidencia
                ->get();
            $participaciones_pendientes = LogroAnexo::where('id_participacion', $participacion->id)
                ->where('validado', 'no')
                ->with('productos')
                ->get();

        }else{
            $participaciones = null;
            $participaciones_pendientes = null;
        }
        

        $completo = [
            'logro' => $logro,
            'participacion' => $participacion,
            'participaciones' => $participaciones,
            'participaciones_pendientes' => $participaciones_pendientes,
        ];

        return response()->json($completo);
    }

    public function detalles_logro_2025_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
        $logro = Logro::find($request->input('id'));
        $permisos = [];
        
        if (!empty($logro->sesiones)) {
            // Convertir la cadena en un array
            $urls = explode(',', $logro->sesiones);
        
            foreach ($urls as $url) {
                $url = trim($url); // limpiar espacios en blanco si los hubiera
        
                $sesion = Sesion::where('id_cuenta', $cuenta->id)
                                ->where('url', $url)
                                ->first(['id', 'titulo', 'url']);
        
                if ($sesion) {
                    $sesionVis = SesionVis::where('id_sesion', $sesion->id)
                                          ->where('id_usuario', $id_usuario)
                                          ->first();
        
                    $permisos[$sesion->url] = [
                        'existe'   => true,
                        'completa' => $sesionVis ? true : false,
                        'titulo'   => $sesion->titulo,
                        'url'   => $sesion->url,
                    ];
                } else {
                    $permisos[$url] = [
                        'existe'   => false,
                        'completa' => false,
                        'titulo'   => null,
                        'url'   => null,
                    ];
                }
            }
        }

        $participacion = LogroParticipacion::where('id_logro', $logro->id)->where('id_usuario', $id_usuario)->first();
        //$participaciones = LogroParticipacion::where('id_logro', $id_usuario)->get();
        if($participacion){

            $participaciones = LogroAnexo::where('id_participacion', $participacion->id)
                ->with('productos') // Carga los productos asociados a cada evidencia
                ->get();
            $participaciones_pendientes = LogroAnexo::where('id_participacion', $participacion->id)
                ->where('validado', 'no')
                ->with('productos')
                ->get();
                $fechaMasAlta = $participaciones->max('fecha_registro');

        }else{
            $participaciones = null;
            $participaciones_pendientes = null;
            $fechaMasAlta  = null;
        }
        

        $completo = [
            'logro' => $logro,
            'participacion' => $participacion,
            'participaciones_logro' => $participaciones,
            'participaciones_logro_pendientes' => $participaciones_pendientes,
            'fecha_ultimo_comprobante' => $fechaMasAlta,
            'permisos' => $permisos
            
        ];

        return response()->json($completo);
    }

    public function participar_logro_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
        $usuario = User::find($id_usuario);
        $id_logro = $request->input('id_logro');
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $id_distribuidor = $suscripcion->id_distribuidor;
        $logro = Logro::find($id_logro);

        $participacion = new LogroParticipacion();
        $participacion->id_logro = $id_logro;
        $participacion->id_temporada = $id_temporada;
        $participacion->id_distribuidor = $id_distribuidor;
        $participacion->id_usuario = $id_usuario;
        $participacion->estado = 'participante';
        $participacion->fecha_registro = date('Y-m-d H:i:s');

        $participacion->save();

        //Mail::to($usuario->email)->send(new DesafioChampions($data));

        return 'guardado';
    }

    public function validar_logro_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
        $id_logro = $request->input('id_logro');
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $id_distribuidor = $suscripcion->id_distribuidor;
        $logro = Logro::find($id_logro);

        $participacion = LogroParticipacion::where('id_logro', $id_logro)->where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $participacion->estado = 'validando';

        $participacion->save();

        return 'actualizado';
    }
    
    public function subir_evidencia_api (Request $request)
    {
        //Log::info('Datos recibidos:', $request->all());

        $request->validate([
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048' // Ajusta las reglas de validación según tus necesidades}
        ]);

        if ($request->hasFile('file')) {
            $archivo = $request->file('file');
            $nombreArchivo = 'evidencia'.time().'.'.$archivo->extension();
            $archivo->move(base_path('../public_html/img/evidencias'), $nombreArchivo);

            $id_cuenta = $request->input('id_cuenta');
            $id_usuario = $request->input('id_usuario');
            $id_logro = $request->input('id_logro');
            $id_participacion = $request->input('id_participacion');
            $cuenta = Cuenta::find($id_cuenta);
            $logro = Logro::find($id_logro);
            $id_temporada = $logro->id_temporada;
            $nivel = $request->input('nivel');
            $folio = $request->input('folio');
            $moneda = $request->input('moneda');
            $emision = $request->input('emision');
            $productos = json_decode($request->input('productos'), true);
            
            $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
            $id_distribuidor = $suscripcion->id_distribuidor;
            

            $evidencia = new LogroAnexo();
            $evidencia->id_logro = $id_logro;
            $evidencia->id_participacion = $id_participacion;
            $evidencia->id_temporada = $id_temporada;
            $evidencia->id_usuario = $id_usuario;
            $evidencia->documento = $nombreArchivo;
            $evidencia->nivel = $nivel;
            $evidencia->folio = $folio;
            $evidencia->moneda = $moneda;
            $evidencia->emision = $emision;
            $evidencia->fecha_registro = date('Y-m-d H:i:s');

            $evidencia->save();

             // Guardar cada producto asociado a la evidencia
         // Guardar cada producto asociado a la evidencia
         if (!empty($productos) && is_array($productos)) {
            foreach ($productos as $producto) {
                $nuevoProducto = new LogroAnexoProducto();
                $nuevoProducto->id_logro = $id_logro;
                $nuevoProducto->id_participacion = $id_participacion;
                $nuevoProducto->id_temporada = $id_temporada;
                $nuevoProducto->id_usuario = $id_usuario;
                $nuevoProducto->id_anexo = $evidencia->id;
                $nuevoProducto->sku = $producto['sku'];  // Antes: $producto->sku (incorrecto)
                $nuevoProducto->cantidad = $producto['cantidad'];
                $nuevoProducto->importe_total = $producto['importe'];
                $nuevoProducto->save();
            }
        }

            return ('Archivo Guardado');
        }else{
            return ('No se envió nada');
        }
        

    }

    public function borrar_evidencia_api(Request $request)
    {
        //
        $evidencia = LogroAnexo::where('documento', $request->id)->first();


        $evidencia->delete();
        return ('borrado');
    }

    public function reporte(Request $request)
    {
        // Validar los parámetros requeridos
        $request->validate([
            'id_temporada' => 'required|integer',
            'id_logro' => 'required|integer',
            'region' => 'required',
        ]);
        
        // Nombre del archivo que se va a descargar
        $nombreArchivo = 'logros_export_' . now()->format('Ymd_His') . '.xlsx';

        // Retornar la descarga
        return Excel::download(new LogrosExport($request), $nombreArchivo);
        
    }
}
