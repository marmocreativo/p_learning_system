<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Cuenta;
use App\Models\Clase;
use App\Models\Temporada;
use App\Models\UsuariosSuscripciones;
use App\Models\Logro;
use App\Models\LogroParticipacion;
use App\Models\LogroAnexo;
use App\Models\User;
use App\Models\Distribuidor;
use Illuminate\Support\Facades\DB;

use App\Mail\ConfirmacionNivelChampions;
use App\Mail\FinalizacionChampions;
use Illuminate\Support\Facades\Mail;


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

         // Validar la solicitud
       $request->validate([
        'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        'ImagenFondo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'logro_'.time().'.'.$imagen->extension();
            $imagen->move(public_path('img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = 'default.jpg';
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_logro_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(public_path('img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = 'default_fondo.jpg';
        }

         $logro->id_temporada = $request->IdTemporada;
         $logro->nombre = $request->Nombre;
         $logro->instrucciones = $request->Instrucciones;
         $logro->contenido = $request->Contenido;
         $logro->premio = $request->Premio;
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
         $logro->fecha_inicio = date('Y-m-d H:i:s', strtotime($request->FechaInicio.' '.$request->HoraInicio));
         $logro->fecha_vigente = date('Y-m-d H:i:s', strtotime($request->FechaVigente.' '.$request->HoraVigente));
 
         $logro->save();
 
         return redirect()->route('logros.show', $logro->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $logro = Logro::find($id);
        
        // Obtener las participaciones y anexar el conteo de anexos
        $participaciones = DB::table('logros_participantes')
            ->join('usuarios', 'logros_participantes.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'logros_participantes.id_distribuidor', '=', 'distribuidores.id')
            ->leftJoin('logros_anexos', function($join) {
                $join->on('logros_anexos.id_participacion', '=', 'logros_participantes.id')
                     ->where('logros_anexos.validado', '=', 'no');
            })
            ->where('logros_participantes.id_logro', '=', $logro->id)
            ->select(
                'logros_participantes.id as id_participacion',
                'logros_participantes.*',
                'usuarios.*',
                'distribuidores.nombre as nombre_distribuidor',
                DB::raw('COUNT(logros_anexos.id) as anexos_no_validados')
            )
            ->groupBy('logros_participantes.id', 'usuarios.id', 'distribuidores.id')
            ->get();
    
        return view('admin/logro_detalles', compact('logro', 'participaciones'));
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
        return view('admin/logro_form_actualizar', compact('logro'));
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
            ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'logro'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $logro->imagen;
        }
        if ($request->hasFile('ImagenFondo')) {
            $imagen_fondo = $request->file('ImagenFondo');
            $nombreImagenFondo = 'fondo_logro_'.time().'.'.$imagen_fondo->extension();
            $imagen_fondo->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagenFondo);
        }else{
            $nombreImagenFondo = $logro->imagen_fondo;
        }

        $logro->id_temporada = $request->IdTemporada;
        $logro->nombre = $request->Nombre;
        $logro->instrucciones = $request->Instrucciones;
        $logro->premio = $request->Premio;
        $logro->contenido = $request->Contenido;
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
         $logro->cantidad_evidencias = $request->CantidadEvidencias;
        $logro->imagen = $nombreImagen;
        $logro->imagen_fondo = $nombreImagenFondo;
        $logro->fecha_inicio = date('Y-m-d H:i:s', strtotime($request->FechaInicio.' '.$request->HoraInicio));
        $logro->fecha_vigente = date('Y-m-d H:i:s', strtotime($request->FechaVigente.' '.$request->HoraVigente));
 
         $logro->save();
 
         return redirect()->route('logros.show', $logro->id);
    }

    public function actualizar_anexo(Request $request, string $id)
    {
        //
        $anexo = LogroAnexo::find($id);
        $anexo->nivel = $request->Nivel;
        $anexo->validado = $request->Validado;
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


        $participacion->delete();
        return redirect()->route('logros', ['id_temporada'=>$id_temporada]);
        
    }

    public function destroy_anexo(string $id)
    {
        //
        $anexo = LogroParticipacion::find($id);
        $id_temporada = $anexo->id_temporada;
        $id_participacion = $anexo->id_participacion;
        // Buscar y eliminar registros relacionados en otras tablas
        
        LogroAnexo::where('id', $anexo->$id)->delete();


        $anexo->delete();
        return redirect()->route('logros.detalles_participacion', ['id'=>$id_temporada]);
        
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
        //
        $participacion = LogroParticipacion::find($id);

        switch ($request->ConfirmacionNivel) {
            case 'a':
                $participacion->confirmacion_nivel_a = 'si';
                $participacion->confirmacion_nivel_b = 'no';
                $participacion->confirmacion_nivel_c = 'no';
                $participacion->confirmacion_nivel_especial = 'no';
                break;
            case 'b':
                $participacion->confirmacion_nivel_a = 'si';
                $participacion->confirmacion_nivel_b = 'si';
                $participacion->confirmacion_nivel_c = 'no';
                $participacion->confirmacion_nivel_especial = 'no';
                break;
            case 'c':
                $participacion->confirmacion_nivel_a = 'si';
                $participacion->confirmacion_nivel_b = 'si';
                $participacion->confirmacion_nivel_c = 'si';
                $participacion->confirmacion_nivel_especial = 'no';
                break;

            case 'especial':
                $participacion->confirmacion_nivel_a = 'si';
                $participacion->confirmacion_nivel_b = 'si';
                $participacion->confirmacion_nivel_c = 'si';
                $participacion->confirmacion_nivel_especial = 'si';
                break;
            
            default:
                # code...
                break;
        }
        $participacion->estado = $request->Estado;


        if($participacion->estado=='finalizado'){
            $data = [
                'titulo' => ' ¡Desafío completado! ',
                'contenido' => '<p>"Has superado los niveles de tu desafio Champions  ¡Gracias por participar, y prepárate para la próxima temporada!</p>',
                'boton_texto' => 'Desafío Champions',
                'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
            ];
            Mail::to($request->UsuarioEmail)->send(new FinalizacionChampions($data));
        }else{
            if($participacion->confirmacion_nivel_especial=='si'){
                $data = [
                    'titulo' => '¡Has subido de nivel',
                    'contenido' => '<p>"¡Felicidades! Un árbitro del Desafío PLearning ha validado tus órdenes de compra y 
                    facturas, y ha declarado que cumpliste con los requisitos para completar el nivel Especial. ¡Continúa participando hasta desbloquear todos los bonos!
                    Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos."</p>
                    
                    
                    <p>Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos.</p>',
                    'boton_texto' => 'Desafío Champions',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
                ];
                Mail::to($request->UsuarioEmail)->send(new ConfirmacionNivelChampions($data));
    
            }elseif($participacion->confirmacion_nivel_c=='si'){
                $data = [
                    'titulo' => '¡Has subido de nivel',
                    'contenido' => '<p>"¡Felicidades! Un árbitro del Desafío PLearning ha validado tus órdenes de compra y 
                    facturas, y ha declarado que cumpliste con los requisitos para completar el nivel C. ¡Continúa participando hasta desbloquear todos los bonos!
                    Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos."</p>
                    
                    
                    <p>Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos.</p>',
                    'boton_texto' => 'Desafío Champions',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
                ];
                Mail::to($request->UsuarioEmail)->send(new ConfirmacionNivelChampions($data));
    
            }elseif($participacion->confirmacion_nivel_b=='si'){
                $data = [
                    'titulo' => '¡Has subido de nivel',
                    'contenido' => '<p>"¡Felicidades! Un árbitro del Desafío PLearning ha validado tus órdenes de compra y 
                    facturas, y ha declarado que cumpliste con los requisitos para completar el nivel B. ¡Continúa participando hasta desbloquear todos los bonos!
                    Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos."</p>
                    
                    
                    <p>Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos.</p>',
                    'boton_texto' => 'Desafío Champions',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
                ];
                Mail::to($request->UsuarioEmail)->send(new ConfirmacionNivelChampions($data));
    
            }elseif($participacion->confirmacion_nivel_a=='si'){
                $data = [
                    'titulo' => '¡Has subido de nivel',
                    'contenido' => '<p>"¡Felicidades! Un árbitro del Desafío PLearning ha validado tus órdenes de compra y 
                    facturas, y ha declarado que cumpliste con los requisitos para completar el nivel A. ¡Continúa participando hasta desbloquear todos los bonos!
                    Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos."</p>
                    
                    
                    <p>Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos.</p>',
                    'boton_texto' => 'Desafío Champions',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
                ];
                Mail::to($request->UsuarioEmail)->send(new ConfirmacionNivelChampions($data));
                
            }
        }

 
         $participacion->save();
 
         return redirect()->route('logros.detalles_participacion', ['id'=>$participacion->id]);
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
                        ->get();
                break;
            
            default:
                $logros = Logro::where('id_temporada', $id_temporada)
                ->where(function($query) use ($distribuidor) {
                    $query->where('region', 'México')
                        ->orWhere('region', 'Interna')
                        ->orWhere('region', 'Todas');
                })
                ->get();
                break;
        }
        
        
        $participaciones = DB::table('logros_participantes')
            ->join('logros', 'logros_participantes.id_logro', '=', 'logros.id')
            ->where('logros_participantes.id_usuario', '=', $id_usuario)
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
            $participaciones = DB::table('logros_anexos')
            ->join('logros', 'logros_anexos.id_logro', '=', 'logros.id')
            ->where('logros_anexos.id_participacion', '=', $participacion->id)
            ->where('logros_anexos.id_usuario', '=', $id_usuario)
            ->select('logros_anexos.*', 'logros.*')
            ->get();
        }else{
            $participaciones = null;
        }
        

        $completo = [
            'logro' => $logro,
            'participacion' => $participacion,
            'participaciones' => $participaciones
        ];

        return response()->json($completo);
    }

    public function participar_logro_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $id_usuario = $request->input('id_usuario');
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

        $request->validate([
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:2048' // Ajusta las reglas de validación según tus necesidades}
        ]);

        if ($request->hasFile('file')) {
            $archivo = $request->file('file');
            $nombreArchivo = 'evidencia'.time().'.'.$archivo->extension();
            $archivo->move(base_path('../public_html/plsystem/img/evidencias'), $nombreArchivo);

            $id_cuenta = $request->input('id_cuenta');
            $id_usuario = $request->input('id_usuario');
            $id_logro = $request->input('id_logro');
            $id_participacion = $request->input('id_participacion');
            $cuenta = Cuenta::find($id_cuenta);
            $logro = Logro::find($id_logro);
            $id_temporada = $logro->id_temporada;
            $nivel = $request->input('nivel');
            
            $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
            $id_distribuidor = $suscripcion->id_distribuidor;
            

            $evidencia = new LogroAnexo();
            $evidencia->id_logro = $id_logro;
            $evidencia->id_participacion = $id_participacion;
            $evidencia->id_temporada = $id_temporada;
            $evidencia->id_usuario = $id_usuario;
            $evidencia->documento = $nombreArchivo;
            $evidencia->nivel = $nivel;
            $evidencia->fecha_registro = date('Y-m-d H:i:s');

            $evidencia->save();

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
}
