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
use App\Models\EvaluacionRes;
use App\Models\PuntosExtra;
use App\Models\TriviaGanador;
use App\Models\TriviaRes;
use App\Models\Trivia;
use App\Models\JackpotIntentos;
use App\Models\JackpotRes;
use App\Models\Jackpot;
use App\Models\CanjeoCortes;
use App\Models\CanjeoCortesUsuarios;
use App\Models\CanjeoProductos;
use App\Models\CanjeoProductosGaleria;
use App\Models\CanjeoTransacciones;
use App\Models\CanjeoTransaccionesProductos;
use App\Models\Cuenta;
use App\Models\Tokens;
use App\Models\AccionesUsuarios;
use App\Models\NotificacionUsuario;
use App\Models\LogroParticipacion;
use App\Models\LogroAnexo;
use App\Models\LogroAnexoProducto;
use App\Models\Logro;
use App\Models\Direccion;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

use App\Mail\CambioPass;
use App\Mail\InscripcionChampions;
use App\Mail\RegistroUsuario;
use Illuminate\Support\Facades\Mail;

use App\Exports\UsersExport;
use App\Exports\UsersRegionExport;
use App\Exports\UsersGeneralExport;
use App\Exports\PuntajeExport;
use Maatwebsite\Excel\Facades\Excel;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('email', 'like', "%{$search}%");
        }

        $usuarios = $query->paginate();

        return view('admin.usuario_lista', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clases = Clase::where('elementos','usuarios')->get();
        return view('admin/usuario_form', compact('clases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
        $usuario = new User();
        $usuario->email = $request->Email;

        $emailPrefix = explode('@', $request->Email)[0];
        do {
            $randomNumbers = rand(100, 999);
            $newLegacyId = $emailPrefix . $randomNumbers;
        } while (User::where('legacy_id', $newLegacyId)->exists());
        $usuario->legacy_id = $newLegacyId;

        $usuario->nombre = $request->Nombre;
        $usuario->apellidos = $request->Apellidos;
        $usuario->telefono = $request->Telefono;
        $usuario->whatsapp = $request->Whatsapp;
        $usuario->fecha_nacimiento = $request->FechaNacimiento;
        $usuario->password = Hash::make($request->Password);
        $usuario->lista_correo = $request->ListaCorreo;
        $usuario->imagen = 'default.jpg';
        $usuario->clase = $request->Clase;
        $usuario->estado = $request->Estado;

        $usuario->save();

        return redirect()->route('admin_usuarios.show', $usuario->id);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
        $usuario = User::find($id);
        return view('admin/usuario_detalles', compact('usuario'));
        

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $clases = Clase::where('elementos','usuarios')->get();
        $usuario = User::find($id);
        return view('admin/usuario_form_actualizar')->with(compact('clases','usuario'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        
        $usuario = User::find($id);

        $usuario->legacy_id = $request->LegacyId;
        $usuario->nombre = $request->Nombre;
        $usuario->apellidos = $request->Apellidos;
        $usuario->email = $request->Email;
        $usuario->telefono = $request->Telefono;
        $usuario->whatsapp = $request->Whatsapp;
        $usuario->fecha_nacimiento = $request->FechaNacimiento;
        $usuario->lista_correo = $request->ListaCorreo;
        $usuario->clase = $request->Clase;
        $usuario->estado = $request->Estado;

        $usuario->save();

        return redirect()->route('admin_usuarios.show', $usuario->id);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        
        $usuario = User::find($id);
        $usuario->delete();
        return redirect()->route('admin_usuarios');
        
    }

    /**
     * Usuarios por temporada
     */
    public function usuarios_suscritos (Request $request)
    {
        //

        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $cuenta = Cuenta::find($temporada->id_cuenta);
        $id_cuenta = $temporada->id_cuenta;
        if($request->input('region')){
            $region = $request->input('region');
            $distribuidores = Distribuidor::where('region', $region)->get();
        }
        else{
            $region = '';
            $distribuidores = Distribuidor::all();
        }
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;
        

        // Obtener la colección de distribuidores
        

        // Extraer los IDs de los distribuidores
        $distribuidorIds = $distribuidores->pluck('id')->toArray();

            // Obtener los suscriptores filtrando por los IDs de distribuidores y la temporada
        $query = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', $id_temporada)
            ->whereIn('usuarios_suscripciones.id_distribuidor', $distribuidorIds)
            ->distinct('usuarios.id');
            
            if ($request->has('search') && !empty($request->input('search'))) {
                $query->where(function($query) use ($request) {
                    $query->where('usuarios.nombre', 'like', '%'.$request->input('search').'%')
                            ->orWhere('usuarios.email', 'like', '%'.$request->input('search').'%')
                            ->orWhere('distribuidores.nombre', 'like', '%'.$request->input('search').'%');
                });
            }

            
            $suscriptores = $query->select( 'usuarios.id as id_usuario',
                                            'usuarios.nombre as nombre_usuario',
                                            'usuarios.password', 'usuarios.*',
                                            'usuarios_suscripciones.id as id_suscripcion',
                                            'usuarios_suscripciones.*',
                                            'distribuidores.id as id_distribuidor',
                                            'distribuidores.nombre as nombre_distribuidor',
                                            'distribuidores.nivel as nivel_distribuidor',
                                            'distribuidores.*')
            ->paginate(10);

            $suscriptores->getCollection()->transform(function ($suscriptor) {
                // Asegúrate de acceder correctamente a la propiedad de la contraseña que necesitas comparar
                $suscriptor->pass_restaurado = Hash::check($suscriptor->default_pass, $suscriptor->password);
                return $suscriptor;
            });


        $clases = Clase::where('elementos','usuarios')->get();
        $distribuidores = Distribuidor::all();
        //$usuarios = UsuariosSuscripciones::where('id_temporada', $id_temporada)->paginate();
        return view('admin/usuario_lista_suscripciones', compact('cuenta', 'temporada', 'suscriptores', 'clases', 'distribuidores', 'cuentas', 'color_barra_superior', 'logo_cuenta'));
    }

    public function usuarios_suscritos_reporte_temporada (Request $request)
    {
        return Excel::download(new UsersGeneralExport($request), 'reporte_usuarios.xlsx');
        
    }

    public function usuarios_suscritos_reporte (Request $request)
    {
        return Excel::download(new UsersExport($request), 'reporte_usuarios.xlsx');
        
    }
    public function usuarios_suscritos_region_reporte (Request $request)
    {
        return Excel::download(new UsersRegionExport($request), 'reporte_usuarios_region.xlsx');
        
    }

    public function usuarios_suscritos_reporte_interno (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_temporada = $request->input('id_temporada');
        $region = $request->input('region');

        // Obtener la colección de distribuidores
        $distribuidores = Distribuidor::where('region', $region)->get();

        // Extraer los IDs de los distribuidores
        $distribuidorIds = $distribuidores->pluck('id')->toArray();

        // Obtener los suscriptores filtrando por los IDs de distribuidores y la temporada
        $suscriptores = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', $id_temporada)
            ->whereIn('usuarios_suscripciones.id_distribuidor', $distribuidorIds)
            ->distinct('usuarios.id')
            ->select('usuarios.id as id_usuario','usuarios.nombre as nombre_usuario', 'usuarios.*', 'usuarios_suscripciones.*', 'distribuidores.nombre as nombre_distribuidor','distribuidores.nivel as nivel_distribuidor','distribuidores.*')
            ->get();

       
        return view('admin/usuario_lista_suscripciones_full', compact('suscriptores'));

        
    }

    public function usuarios_suscritos_puntaje (Request $request)
    {
        return Excel::download(new PuntajeExport($request), 'puntaje_usuarios.xlsx');
        
    }

    public function usuarios_suscritos_puntos_extra (Request $request)
    {
        //

        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $cuenta = Cuenta::find($temporada->id_cuenta);
        $id_cuenta = $temporada->id_cuenta;
        if($request->input('region')){
            $region = $request->input('region');
            $distribuidores = Distribuidor::where('region', $region)->get();
        }
        else{
            $region = '';
            $distribuidores = Distribuidor::all();
        }
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;
        

        // Obtener la colección de distribuidores
        

        // Extraer los IDs de los distribuidores
        $distribuidorIds = $distribuidores->pluck('id')->toArray();

            // Obtener los suscriptores filtrando por los IDs de distribuidores y la temporada
        $query = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->join('distribuidores', 'usuarios_suscripciones.id_distribuidor', '=', 'distribuidores.id')
            ->where('usuarios_suscripciones.id_temporada', $id_temporada)
            ->whereIn('usuarios_suscripciones.id_distribuidor', $distribuidorIds)
            ->distinct('usuarios.id');
            
            if ($request->has('search') && !empty($request->input('search'))) {
                $query->where(function($query) use ($request) {
                    $query->where('usuarios.nombre', 'like', '%'.$request->input('search').'%')
                            ->orWhere('usuarios.email', 'like', '%'.$request->input('search').'%')
                            ->orWhere('distribuidores.nombre', 'like', '%'.$request->input('search').'%');
                });
            }

            
            $suscriptores = $query->select( 'usuarios.id as id_usuario',
                                            'usuarios.nombre as nombre_usuario',
                                            'usuarios.password', 'usuarios.*',
                                            'usuarios_suscripciones.id as id_suscripcion',
                                            'usuarios_suscripciones.*',
                                            'distribuidores.id as id_distribuidor',
                                            'distribuidores.nombre as nombre_distribuidor',
                                            'distribuidores.nivel as nivel_distribuidor',
                                            'distribuidores.*')
            ->paginate(30);

        foreach ($suscriptores as $suscriptor) {
            $puntos_extra = DB::table('puntos_extra')
                ->where('id_usuario', $suscriptor->id_usuario)
                ->where('id_temporada', $id_temporada)
                ->select('id', 'puntos', 'concepto', 'fecha_registro')
                ->get();
        
            // Añade la información de puntos_extra al suscriptor
            $suscriptor->puntos_extra = $puntos_extra;
        }


        $clases = Clase::where('elementos','usuarios')->get();
        $distribuidores = Distribuidor::all();
        //$usuarios = UsuariosSuscripciones::where('id_temporada', $id_temporada)->paginate();
        return view('admin/usuario_lista_puntos_extra', compact('cuenta', 'temporada', 'cuentas', 'color_barra_superior', 'logo_cuenta', 'suscriptores', 'clases', 'distribuidores'));
    }

    public function usuarios_agregar_puntos_extra(Request $request)
    {
        $puntaje = new PuntosExtra();
        $puntaje->id_cuenta = $request->input('IdCuenta');
        $puntaje->id_temporada = $request->input('IdTemporada');
        $puntaje->id_usuario = $request->input('IdUsuario');
        $puntaje->concepto = $request->input('Concepto');
        $puntaje->puntos = $request->input('Puntos');
        $puntaje->fecha_registro = date('Y-m-d H:i:s');

        $puntaje->save();

        $queryParams = [];

        $queryParams['id_temporada'] = $request->input('IdTemporada');

        // Verifica si el parámetro search está presente y no está vacío
        if ($request->has('Search') && !empty($request->input('Search'))) {
            $queryParams['search'] = $request->input('Search');
        }

        // Verifica si el parámetro region está presente y no está vacío
        if ($request->has('Region') && !empty($request->input('Region'))) {
            $queryParams['region'] = $request->input('Region');
        }

        return redirect()->route('admin_usuarios_puntos_extra', $queryParams);

    }

    public function usuarios_borrar_puntos_extra($id, Request $request)
    {
        $puntaje = PuntosExtra::find($id);
        $puntaje->delete();
        $queryParams = [];

        $queryParams['id_temporada'] = $request->input('IdTemporada');

        // Verifica si el parámetro search está presente y no está vacío
        if ($request->has('Search') && !empty($request->input('Search'))) {
            $queryParams['search'] = $request->input('Search');
        }

        // Verifica si el parámetro region está presente y no está vacío
        if ($request->has('Region') && !empty($request->input('Region'))) {
            $queryParams['region'] = $request->input('Region');
        }

        return redirect()->route('admin_usuarios_puntos_extra', $queryParams);
    }




    public function suscribir_update(Request $request, string $id)
{
    try {
        $suscripcion = UsuariosSuscripciones::find($id);
        $id_usuario = $suscripcion->id_usuario;
        $usuario = User::find($id_usuario);
        $id_temporada = $request->IdTemporada;

        // Actualizo
        $suscripcion->id_distribuidor = $request->IdDistribuidor;
        $suscripcion->funcion = $request->Funcion;
        $suscripcion->nivel_usuario = $request->NivelUsuario;
        $suscripcion->champions_a = $request->ChampionsA;
        $suscripcion->champions_b = $request->ChampionsB;
        $suscripcion->save();

        // Reasigno el distribuidor en las actividades
        $visualizaciones = SesionVis::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach ($visualizaciones as $visualizacion) {
            $visualizacion->id_distribuidor = $request->IdDistribuidor;
            $visualizacion->save();
        }

        $evaluaciones_respuestas = EvaluacionRes::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach ($evaluaciones_respuestas as $respuesta) {
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $trivias_respuestas = TriviaRes::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach ($trivias_respuestas as $respuesta) {
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $trivias_ganadores = TriviaGanador::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach ($trivias_ganadores as $ganador) {
            $ganador->id_distribuidor = $request->IdDistribuidor;
            $ganador->save();
        }

        $jackpot_respuestas = JackpotRes::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach ($jackpot_respuestas as $respuesta) {
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $jackpot_intentos = JackpotIntentos::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach ($jackpot_intentos as $intento) {
            $intento->id_distribuidor = $request->IdDistribuidor;
            $intento->save();
        }

        // Si cumple con los requisitos para Champions, intentamos enviar el correo
        if ($request->ChampionsA == 'si' && $request->ChampionsB == 'si') {
            $data = [
                'titulo' => '¡Has sido elegido para el Desafío Champios de Panduit!',
                'contenido' => '<p>¡Bienvenido al Desafío Champions! Debido a tu participación destacada en la temporada anterior y a que participaste en todas las sesiones, te extendemos la invitación a participar en un desafío especial, para los mejores de PLearning, en el que podrás ganar incentivos económicos independientes de tu participación en el programa.</p>
                <p>• Elige una categoría entre oas que están disponibles.</p>
                <p>• Vende los productos participantes para subir de nivel.</p>
                <p>• Comprueba tus ventas con facturas y órdenes de compra.</p>
                <p>• Recibe el bono del nivel del desafío superado. Son acumulables.</p>
                
                <p>Hay más información en el sitio web; ¡esperamos que aceptes el reto y te deseamos un gran éxito!</p>
                
                
                <p>Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos.</p>',
                'boton_texto' => 'Desafío Champions',
                'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
            ];
            
            try {
                Mail::to($usuario->email)->send(new InscripcionChampions($data));
            } catch (\Exception $e) {
                // Registramos el error pero continuamos con la ejecución
                \Log::error('Error al enviar correo de inscripción Champions: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin_usuarios_suscritos', ['id_temporada' => $request->IdTemporada]);
        
    } catch (\Exception $e) {
        \Log::error('Error en suscribir_update: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Ha ocurrido un error al actualizar la suscripción.']);
    }
}

public function suscribir_full_update(Request $request, string $id)
{
    try {
        $suscripcion = UsuariosSuscripciones::find($id);

        $id_usuario = $suscripcion->id_usuario;
        $usuario = User::find($id_usuario);
        $id_temporada = $request->IdTemporada;
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);

        // Actualizo usuario
        $usuario->nombre = $request->Nombre;
        $usuario->apellidos = $request->Apellidos;
        $usuario->whatsapp = $request->Whatsapp;
        $usuario->save(); // Esta línea faltaba en el código original

        
        // Actualizo suscripción
        $suscripcion->id_distribuidor = $request->IdDistribuidor;
        $suscripcion->funcion = $request->Funcion;
        $suscripcion->funcion_region = $request->FuncionRegion;
        $suscripcion->nivel_usuario = $request->NivelUsuario;
        $suscripcion->champions_a = $request->ChampionsA;
        $suscripcion->champions_b = $request->ChampionsB;
        $suscripcion->save();
        
        // Reasigno el distribuidor en las actividades
        $visualizaciones = SesionVis::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach($visualizaciones as $visualizacion){
            $visualizacion->id_distribuidor = $request->IdDistribuidor;
            $visualizacion->save();
        }

        $evaluaciones_respuestas = EvaluacionRes::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach($evaluaciones_respuestas as $respuesta){
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $trivias_respuestas = TriviaRes::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach($trivias_respuestas as $respuesta){
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $trivias_ganadores = TriviaGanador::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach($trivias_ganadores as $ganador){
            $ganador->id_distribuidor = $request->IdDistribuidor;
            $ganador->save();
        }

        $jackpot_respuestas = JackpotRes::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach($jackpot_respuestas as $respuesta){
            $respuesta->id_distribuidor = $request->IdDistribuidor;
            $respuesta->save();
        }

        $jackpot_intentos = JackpotIntentos::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach($jackpot_intentos as $intento){
            $intento->id_distribuidor = $request->IdDistribuidor;
            $intento->save();
        }

        // Cambio el nivel del distribuidor
        $suscripciones_actualizar = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_distribuidor', $request->IdDistribuidor)->get();
        foreach($suscripciones_actualizar as $susc){
            $susc->nivel = $request->NivelDistribuidor;
            $susc->save();
        }

        // Verificamos si debemos enviar el correo de Champions
        if ($request->has('CorreoChampions') && $request->CorreoChampions == 1) {
            if($request->ChampionsA == 'si' && $request->ChampionsB == 'si') {
                $data = [
                    'titulo' => '¡Has sido elegido para el Desafío Champios de Panduit!',
                    'contenido' => '<p>¡Bienvenido al Desafío Champions! Debido a tu participación destacada en la temporada anterior y a que participaste en todas las sesiones, te extendemos la invitación a participar en un desafío especial, para los mejores de PLearning, en el que podrás ganar incentivos económicos independientes de tu participación en el programa.</p>
                    <p>• Elige una categoría entre oas que están disponibles.</p>
                    <p>• Vende los productos participantes para subir de nivel.</p>
                    <p>• Comprueba tus ventas con facturas y órdenes de compra.</p>
                    <p>• Recibe el bono del nivel del desafío superado. Son acumulables.</p>
                    
                    <p>Hay más información en el sitio web; ¡esperamos que aceptes el reto y te deseamos un gran éxito!</p>
                    
                    
                    <p>Si recibiste este correo por error o necesitas comunicarte con nosotros, contáctanos.</p>',
                    'boton_texto' => 'Desafío Champions',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/champions'
                ];
                
                try {
                    Mail::to($usuario->email)->send(new InscripcionChampions($data));
                } catch (\Exception $e) {
                    // Registramos el error pero continuamos con la ejecución
                    \Log::error('Error al enviar correo de inscripción Champions: ' . $e->getMessage());
                }
            }
        }
        
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada' => $request->IdTemporada]);
        
    } catch (\Exception $e) {
        \Log::error('Error en suscribir_full_update: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Ha ocurrido un error al actualizar la información. ' . $e->getMessage()]);
    }
}
    public function suscripcion(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $clases = Clase::where('elementos','usuarios')->get();
        $distribuidores = Distribuidor::all();
        return view('admin/usuario_form_suscripcion', compact('clases', 'temporada', 'distribuidores'));
    }

    public function suscribir(Request $request)
    {
            // Verificar si el usuario ya existe
        $usuario = User::where('email', $request->Email)->first();
        

        if (!$usuario) {
            $usuario = new User();
            
            $usuario->email = $request->Email;

            $emailPrefix = explode('@', $request->Email)[0];
            do {
                $randomNumbers = rand(100, 999);
                $newLegacyId = $emailPrefix . $randomNumbers;
            } while (User::where('legacy_id', $newLegacyId)->exists());
            $usuario->legacy_id = $newLegacyId;

            $telefono = '';
            if(!empty($request->Telefono)){
                $telefono = $request->Telefono;
            }
            
            $whatsapp = '';
            if(!empty($request->Whatsapp)){
                $whatsapp = $request->Whatsapp;
            }
            
            $fecha_nacimiento = null;

            $usuario->nombre = $request->Nombre;
            $usuario->apellidos = $request->Apellidos;
            $usuario->telefono = $telefono;
            $usuario->whatsapp = $whatsapp;
            $usuario->fecha_nacimiento = $request->FechaNacimiento;
            $usuario->password = Hash::make($request->Password);
            $usuario->lista_correo = $request->ListaCorreo;
            $usuario->imagen = 'default.jpg';
            $usuario->clase = $request->Clase;
            $usuario->estado = $request->Estado;

            $usuario->save();
        }


        $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', $request->IdTemporada)->first();
        if (!$suscripcion) {
            $suscripcion = new UsuariosSuscripciones();
            $suscripcion->id_usuario = $usuario->id;
            $suscripcion->id_cuenta = $request->IdCuenta;
            $suscripcion->id_temporada = $request->IdTemporada;
            $suscripcion->id_distribuidor = $request->IdDistribuidor;
            $suscripcion->confirmacion_puntos = 'pendiente';
            $suscripcion->funcion = 'usuario';
            $suscripcion->save();
        }
        
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$request->IdTemporada]);
        
    }

    public function borrar_tokens(Request $request)
    {
        // Elimina todos los tokens de un usuario por su 'tokenable_id'
        PersonalAccessToken::where('tokenable_id', $request->id)->delete();
        
        return redirect()->back();
    }

    public function cambiar_a_lider(Request $request)
    {

        $suscripcion = UsuariosSuscripciones::find($request->id);

        if ($suscripcion) {
            $suscripcion->funcion = 'lider';
            $suscripcion->save();
        }
        
        return redirect()->back();

        
    }

    public function cambiar_a_usuario(Request $request)
    {

        $suscripcion = UsuariosSuscripciones::find($request->id);
        if ($suscripcion) {
            $suscripcion->funcion = 'usuario';
            $suscripcion->save();
        }
        
        return redirect()->back();
        
    }

    public function desuscribir(Request $request, string $id)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $suscripcion = UsuariosSuscripciones::find($id);
        $suscripcion->delete();
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$id_temporada]);
        
    }

    public function restaurar_pass(Request $request){
       

        $usuario = User::find($request->input('id_usuario'));
        $id_distribuidor = $request->input('id_distribuidor');
        $id_temporada = $request->input('id_temporada');
        $distribuidor = Distribuidor::find($request->input('id_distribuidor'));
        

        $usuario->password = Hash::make($distribuidor->default_pass);
        $usuario->save();        
        return redirect()->route('admin_usuarios_suscritos', ['id_temporada'=>$id_temporada]);

    }

    
    /**
     * Display the specified resource.
     */
    public function reporte_sesiones(string $id)
    {
        //
        /*
        $suscripcion = UsuariosSuscripciones::find($id);
        $usuario = User::find($suscripcion->id_usuario);
        $temporada = Temporada::find($suscripcion->id_temporada);
        $sesiones_actuales = SesionEv::where('id_temporada', $temporada->id)->get();
        $sesiones_anteriores = SesionEv::where('id_temporada', $temporada->temporada_anterior)->get();
        $visualizaciones_actuales = SesionVis::where('id_temporada', $temporada->id)->where('id_usuario', $usuario->id)->get();
        $visualizaciones_anteriores = SesionVis::where('id_temporada', $temporada->temporada_anterior)->where('id_usuario', $usuario->id)->get();
        $acciones = AccionesUsuarios::where('id_usuario', $usuario->id)->orderBy('created_at', 'desc')->get();
        */
        $suscripcion = UsuariosSuscripciones::find($id);
        $temporada = Temporada::find($suscripcion->id_temporada);
        $id_temporada = $temporada->id;
        $id_usuario = $suscripcion->id_usuario;
        $usuario = User::find($suscripcion->id_usuario);
        $cuenta = Cuenta::find($temporada->id_cuenta);
        $cuentas = Cuenta::all();
        $color_barra_superior = $cuenta->fondo_menu;
        $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;
        // datos
        $usuario = User::find($id_usuario);
        // notificaciones y línea del tiempo
        $acciones = AccionesUsuarios::where('id_usuario', $id_usuario)
            ->latest() // Ordena por la columna de timestamps (created_at por defecto)
            ->get();

        // Puntajes
        $suma_visualizaciones = SesionVis::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $suma_evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        
        $sesiones = DB::table('sesiones')
            ->join('sesiones_visualizaciones', 'sesiones.id', '=', 'sesiones_visualizaciones.id_sesion')
            ->where('sesiones.id_temporada', '=', $id_temporada)
            ->where('sesiones_visualizaciones.id_usuario', '=', $id_usuario)
            ->select('sesiones.id as id_sesion', 'sesiones.*', 'sesiones_visualizaciones.*')
            ->get();

        $evaluaciones = DB::table('evaluaciones_preguntas')
        ->join('evaluaciones_respuestas', 'evaluaciones_preguntas.id', '=', 'evaluaciones_respuestas.id_pregunta')
        ->where('evaluaciones_respuestas.id_temporada', '=', $id_temporada)
        ->where('evaluaciones_respuestas.id_usuario', '=', $id_usuario)
        ->select('evaluaciones_preguntas.*', 'evaluaciones_respuestas.*')
        ->get();

        $suma_trivias = TriviaRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();

        $trivias_ganadores = DB::table('trivias')
        ->join('trivias_ganadores', 'trivias.id', '=', 'trivias_ganadores.id_trivia')
        ->where('trivias.id_temporada', '=', $id_temporada)
        ->where('trivias_ganadores.id_usuario', '=', $id_usuario)
        ->select('trivias.*', 'trivias_ganadores.*')
        ->get();

        $trivias_respuestas = DB::table('trivias')
        ->join('trivias_preguntas', 'trivias.id', '=', 'trivias_preguntas.id_trivia')
        ->join('trivias_respuestas', 'trivias_preguntas.id', '=', 'trivias_respuestas.id_pregunta')
        ->where('trivias.id_temporada', '=', $id_temporada)
        ->where(function ($query) {
            $query->whereNull('trivias.id_jackpot')
                  ->orWhere('trivias.id_jackpot', '');
        })
        ->where('trivias_respuestas.id_usuario', '=', $id_usuario)
        ->select('trivias.*', 'trivias_preguntas.*' , 'trivias_respuestas.*')
        ->get();

        $jackpot_intentos = DB::table('jackpot')
        ->join('jackpot_intentos', 'jackpot.id', '=', 'jackpot_intentos.id_jackpot')
        ->where('jackpot.id_temporada', '=', $id_temporada)
        ->where('jackpot.en_trivia', '=', 'no')
        ->where('jackpot_intentos.id_usuario', '=', $id_usuario)
        ->select('jackpot.*', 'jackpot_intentos.*' )
        ->get();

        $trivias_con_jackpot = DB::table('trivias')
        ->join('jackpot_intentos', 'trivias.id_jackpot', '=', 'jackpot_intentos.id_jackpot')
        ->where('trivias.id_temporada', '=', $id_temporada)
        ->whereNotNull('trivias.id_jackpot')
        ->where('trivias.id_jackpot', '!=', '')
        ->where('jackpot_intentos.id_usuario', '=', $id_usuario)
        ->select('trivias.titulo', 'trivias.id_jackpot', 'jackpot_intentos.*')
        ->get();

        $suma_jackpots = JackpotIntentos::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();

        $suma_extra = PuntosExtra::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntos')->sum();

        $puntos_extra = PuntosExtra::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();

        $creditos_redimidos = CanjeoTransacciones::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('creditos')->sum();

        $transacciones = CanjeoTransacciones::with('productos')->where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        $participaciones = LogroParticipacion::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        $anexos = LogroAnexo::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();


        return view('admin/usuario_reporte_sesiones', compact(
            'suscripcion',
            'usuario',
            'cuenta',
            'cuentas',
            'color_barra_superior',
            'logo_cuenta',
            'temporada',
            'suma_visualizaciones',
            'suma_evaluaciones',
            'suma_trivias',
            'suma_jackpots',
            'suma_extra',
            'creditos_redimidos',
            'acciones',
            'sesiones',
            'evaluaciones',
            'trivias_ganadores',
            'trivias_respuestas',
            'jackpot_intentos',
            'trivias_con_jackpot',
            'transacciones',
            'participaciones',
            'anexos',
        ));
        

    }

    /**
     * API Usuarios
     */

    public function usuarios_suscritos_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $suscripciones = DB::table('usuarios')
            ->join('usuarios_suscripciones', 'usuarios.id', '=', 'usuarios_suscripciones.id_usuario')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->select('usuarios.*', 'usuarios_suscripciones.*')
            ->get();
        //$usuarios = UsuariosSuscripciones::where('id_temporada', $id_temporada)->paginate();
        return response()->json($suscripciones);
    }
    public function puntaje_usuario_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $id_usuario = $request->input('id_usuario');
        $visualizaciones = SesionVis::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $trivia = TriviaRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();

        
        $extra = PuntosExtra::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntos')->sum();
        $creditos_redimidos = CanjeoTransacciones::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('creditos')->sum();


        $puntajes = [
            'visualizaciones' =>$visualizaciones,
            'evaluaciones' =>$evaluaciones,
            'trivia' =>$trivia,
            'jackpots' =>$jackpots,
            'extra' =>$extra,
            'creditos_redimidos' => $creditos_redimidos,
        ];
        return response()->json($puntajes);
    }

    public function detalles_puntaje_usuario_api (Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $id_usuario = $request->input('id_usuario');
        $sesiones = DB::table('sesiones')
            ->join('sesiones_visualizaciones', 'sesiones.id', '=', 'sesiones_visualizaciones.id_sesion')
            ->where('sesiones.id_temporada', '=', $id_temporada)
            ->where('sesiones_visualizaciones.id_usuario', '=', $id_usuario)
            ->select('sesiones.id as id_sesion', 'sesiones.*', 'sesiones_visualizaciones.*')
            ->get();

        $evaluaciones = DB::table('evaluaciones_preguntas')
        ->join('evaluaciones_respuestas', 'evaluaciones_preguntas.id', '=', 'evaluaciones_respuestas.id_pregunta')
        ->where('evaluaciones_respuestas.id_temporada', '=', $id_temporada)
        ->where('evaluaciones_respuestas.id_usuario', '=', $id_usuario)
        ->select('evaluaciones_preguntas.*', 'evaluaciones_respuestas.*')
        ->get();

        $trivias_ganadores = DB::table('trivias')
        ->join('trivias_ganadores', 'trivias.id', '=', 'trivias_ganadores.id_trivia')
        ->where('trivias.id_temporada', '=', $id_temporada)
        ->where('trivias_ganadores.id_usuario', '=', $id_usuario)
        ->select('trivias.*', 'trivias_ganadores.*')
        ->get();

        $trivias_respuestas = DB::table('trivias')
        ->join('trivias_preguntas', 'trivias.id', '=', 'trivias_preguntas.id_trivia')
        ->join('trivias_respuestas', 'trivias_preguntas.id', '=', 'trivias_respuestas.id_pregunta')
        ->where('trivias.id_temporada', '=', $id_temporada)
        ->where(function ($query) {
            $query->whereNull('trivias.id_jackpot')
                  ->orWhere('trivias.id_jackpot', '');
        })
        ->where('trivias_respuestas.id_usuario', '=', $id_usuario)
        ->select('trivias.*', 'trivias_preguntas.*' , 'trivias_respuestas.*')
        ->get();

        $jackpot_intentos = DB::table('jackpot')
        ->join('jackpot_intentos', 'jackpot.id', '=', 'jackpot_intentos.id_jackpot')
        ->where('jackpot.id_temporada', '=', $id_temporada)
        ->where('jackpot.en_trivia', '=', 'no')
        ->where('jackpot_intentos.id_usuario', '=', $id_usuario)
        ->select('jackpot.*', 'jackpot_intentos.*' )
        ->get();

        $puntos_extra = PuntosExtra::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();


        $puntajes = [
            'sesiones' =>$sesiones,
            'evaluaciones' => $evaluaciones,
            'trivias_respuestas' => $trivias_respuestas,
            'trivias_ganadores' => $trivias_ganadores,
            'jackpot_intentos' => $jackpot_intentos,
            'puntos_extra'=> $puntos_extra
        ];
        return response()->json($puntajes);
    }

    /**
     * API Usuarios 2025
     */

     public function aceptar_terminos_2025(Request $request)
    {
        $id_suscripcion = $request->input('id_suscripcion');
        $suscripcion = UsuariosSuscripciones::find($id_suscripcion);

        if (!$suscripcion) {
            return response()->json(['success' => false, 'message' => 'Suscripción no encontrada'], 404);
        }

        $suscripcion->fecha_terminos = now();
        
        if ($suscripcion->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function puntaje_usuario_2025(Request $request)
    {
        $id_temporada = $request->input('id_temporada');
        $id_usuario = $request->input('id_usuario');
        // datos
        $usuario = User::find($id_usuario);
        // notificaciones y línea del tiempo
        $direcciones = Direccion::where('id_usuario', $id_usuario)->get();
        $notificaciones = NotificacionUsuario::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        $acciones = AccionesUsuarios::where('id_usuario', $id_usuario)
            ->latest() // Ordena por la columna de timestamps (created_at por defecto)
            ->take(10) // Toma las últimas 10 acciones
            ->get();

        // Puntajes
        $suma_visualizaciones = SesionVis::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        $suma_evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();
        
        $sesiones = DB::table('sesiones')
            ->join('sesiones_visualizaciones', 'sesiones.id', '=', 'sesiones_visualizaciones.id_sesion')
            ->where('sesiones.id_temporada', '=', $id_temporada)
            ->where('sesiones_visualizaciones.id_usuario', '=', $id_usuario)
            ->select('sesiones.id as id_sesion', 'sesiones.*', 'sesiones_visualizaciones.*')
            ->get();

        $evaluaciones = DB::table('evaluaciones_preguntas')
        ->join('evaluaciones_respuestas', 'evaluaciones_preguntas.id', '=', 'evaluaciones_respuestas.id_pregunta')
        ->where('evaluaciones_respuestas.id_temporada', '=', $id_temporada)
        ->where('evaluaciones_respuestas.id_usuario', '=', $id_usuario)
        ->select('evaluaciones_preguntas.*', 'evaluaciones_respuestas.*')
        ->get();

        $suma_trivias = TriviaRes::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();

        $trivias_ganadores = DB::table('trivias')
        ->join('trivias_ganadores', 'trivias.id', '=', 'trivias_ganadores.id_trivia')
        ->where('trivias.id_temporada', '=', $id_temporada)
        ->where('trivias_ganadores.id_usuario', '=', $id_usuario)
        ->select('trivias.*', 'trivias_ganadores.*')
        ->get();

        $trivias_respuestas = DB::table('trivias')
        ->join('trivias_preguntas', 'trivias.id', '=', 'trivias_preguntas.id_trivia')
        ->join('trivias_respuestas', 'trivias_preguntas.id', '=', 'trivias_respuestas.id_pregunta')
        ->where('trivias.id_temporada', '=', $id_temporada)
        ->where(function ($query) {
            $query->whereNull('trivias.id_jackpot')
                  ->orWhere('trivias.id_jackpot', '');
        })
        ->where('trivias_respuestas.id_usuario', '=', $id_usuario)
        ->select('trivias.*', 'trivias_preguntas.*' , 'trivias_respuestas.*')
        ->get();

        $jackpot_intentos = DB::table('jackpot')
        ->join('jackpot_intentos', 'jackpot.id', '=', 'jackpot_intentos.id_jackpot')
        ->where('jackpot.id_temporada', '=', $id_temporada)
        ->where('jackpot.en_trivia', '=', 'no')
        ->where('jackpot_intentos.id_usuario', '=', $id_usuario)
        ->select('jackpot.*', 'jackpot_intentos.*' )
        ->get();

        $trivias_con_jackpot = DB::table('trivias')
        ->join('jackpot_intentos', 'trivias.id_jackpot', '=', 'jackpot_intentos.id_jackpot')
        ->where('trivias.id_temporada', '=', $id_temporada)
        ->whereNotNull('trivias.id_jackpot')
        ->where('trivias.id_jackpot', '!=', '')
        ->where('jackpot_intentos.id_usuario', '=', $id_usuario)
        ->select('trivias.titulo', 'trivias.id_jackpot', 'jackpot_intentos.*')
        ->get();

        $suma_jackpots = JackpotIntentos::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntaje')->sum();

        $suma_extra = PuntosExtra::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('puntos')->sum();

        $puntos_extra = PuntosExtra::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();

        $creditos_redimidos = CanjeoTransacciones::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->pluck('creditos')->sum();

        $transacciones = CanjeoTransacciones::with('productos')->where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        $participaciones = LogroParticipacion::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        $anexos = LogroAnexo::where('id_usuario',$id_usuario)->where('id_temporada',$id_temporada)->get();
        


        $datos_usuario = [
            'usuario' =>$usuario,
            'direcciones' =>$direcciones,
            'notificaciones' =>$notificaciones,
            'acciones' =>$acciones,
            'sesiones' =>$sesiones,
            'evaluaciones' => $evaluaciones,
            'trivias_respuestas' => $trivias_respuestas,
            'trivias_ganadores' => $trivias_ganadores,
            'jackpot_intentos' => $jackpot_intentos,
            'puntos_extra'=> $puntos_extra,
            'trivias_con_jackpot' => $trivias_con_jackpot,
            'suma_visualizaciones'=>$suma_visualizaciones,
            'suma_evaluaciones'=>$suma_evaluaciones,
            'suma_trivias'=>$suma_trivias,
            'suma_jackpots'=>$suma_jackpots,
            'suma_extra'=>$suma_extra,
            'creditos_redimidos'=>$creditos_redimidos,
            'transacciones'=>$transacciones,
            'participaciones'=>$participaciones,
            'anexos'=>$anexos,
        ];
        return response()->json($datos_usuario);
    }

    /**
     * API Lideres
     */
    public function datos_lider_api (Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $sesiones = SesionEv::where('id_temporada', $id_temporada)->get();
        $trivias = Trivia::where('id_temporada', $id_temporada)->get();
        $jackpots = Jackpot::where('id_temporada', $id_temporada)->get();
       
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
        $suscriptores = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->where('usuarios_suscripciones.id_distribuidor', '=', $suscripcion->id_distribuidor)
            ->where('usuarios_suscripciones.id_sucursal', '=', $suscripcion->id_sucursal)
            ->select('usuarios.nombre', 'usuarios.apellidos','usuarios.email', 'usuarios_suscripciones.*')
            ->get();
        $participaciones = array();
        $ids = array();
        $logins = 0;
        $hoy = Carbon::now();
        $inicio = $hoy->copy()->subDays(7);
        $fin = $hoy->copy()->addDays(7);

        foreach($suscriptores as $suscriptor){
            $n_visualizaciones = SesionVis::where('id_usuario', $suscriptor->id_usuario)->count();
            $n_evaluaciones = EvaluacionRes::where('id_usuario', $suscriptor->id_usuario)->distinct('id_usuario')->count();
            $n_trivias = TriviaRes::where('id_usuario', $suscriptor->id_usuario)->groupBy('id_usuario')->count();
            $n_jackpots = TriviaRes::where('id_usuario', $suscriptor->id_usuario)->groupBy('id_usuario')->count();
            $n_extras = 0;
            $participaciones[$suscriptor->id_usuario] = [
                'nombre' => $suscriptor->nombre,
                'apellidos' => $suscriptor->apellidos,
                'visualizaciones' => $n_visualizaciones,
                'evaluaciones' => $n_evaluaciones,
                'trivias' => $n_trivias,
                'jackpots' => $n_jackpots,
                'extras' => $n_extras
            ];
            $ids[] = $suscriptor->id_usuario;
        }
        $activos = 0;
        foreach($participaciones as $participacion){
            if(
                $participacion['visualizaciones']>0||
                $participacion['evaluaciones']>0||
                $participacion['trivias']>0||
                $participacion['jackpots']>0
                ){
                $activos ++;
            }
        } 

        //Bucle de fechas

        
        //Bucle de fechas
        $array_fechas = array();
        $array_visualizaciones = array();
        $array_evaluaciones = array();
        $array_trivias = array();
        $array_jackpots = array();
        for ($date = $inicio; $date->lessThanOrEqualTo($fin); $date->addDay()) {
            // Recorre todas las sesiones
            $conteo_visualizaciones = 0;
            $conteo_evaluaciones = 0;
            $conteo_trivias = 0;
            $conteo_jackpot = 0;
            foreach ($sesiones as $sesion) {
                // Recorre todos los IDs
                foreach ($ids as $id) {
                    // Realiza la consulta
                    $visualizaciones = SesionVis::where('id_sesion', $sesion->id)
                        ->where('id_usuario', $id)
                        ->whereDate('fecha_ultimo_video', $date->format('Y-m-d'))
                        ->count();
                    $evaluaciones = EvaluacionRes::where('id_sesion', $sesion->id)
                        ->where('id_usuario', $id)
                        ->whereDate('fecha_registro', $date->format('Y-m-d'))
                        ->distinct('id_usuario')->count();
                    $conteo_visualizaciones +=$visualizaciones;
                    $conteo_evaluaciones +=$evaluaciones;
                }
                $array_visualizaciones[]= $conteo_visualizaciones;
                $array_evaluaciones[]= $conteo_evaluaciones;
            }
            foreach ($trivias as $trivia) {
                // Recorre todos los IDs
                foreach ($ids as $id) {
                    // Realiza la consulta
                    $respuestas = TriviaRes::where('id_trivia', $trivia->id)
                        ->where('id_usuario', $id)
                        ->whereDate('fecha_registro', $date->format('Y-m-d'))
                        ->distinct('id_usuario')->count();
                    $conteo_trivias += $respuestas;
                }
                $array_trivias[]= $conteo_trivias;
            }

            foreach ($jackpots as $jackpot) {
                // Recorre todos los IDs
                foreach ($ids as $id) {
                    // Realiza la consulta
                    $respuestas = JackpotIntentos::where('id_jackpot', $jackpot->id)
                        ->where('id_usuario', $id)
                        ->whereDate('fecha_registro', $date->format('Y-m-d'))
                        ->distinct('id_usuario')->count();
                    $conteo_jackpot += $respuestas;
                }
                $array_jackpots[]= $conteo_jackpot;
            }
            $array_fechas[] = $date->format('Y-m-d');
        }

        $total_visualizaciones = 0;
        $total_evaluaciones = 0;
        $total_trivias = 0;
        $total_jackpots = 0;
        
        foreach($participaciones as $participacion){
            $total_visualizaciones += $participacion['visualizaciones'];
            $total_evaluaciones += $participacion['evaluaciones'];
            $total_trivias += $participacion['trivias'];
            $total_jackpots += $participacion['jackpots'];
        }

        $completo = [
            'usuario' => $usuario,
            'temporada' => $temporada,
            'suscripcion' => $suscripcion,
            'distribuidor' => $distribuidor,
            'suscriptores' => $suscriptores,
            'participaciones' => $participaciones,
            'activos' => $activos,
            'total_visualizaciones' => $total_visualizaciones,
            'total_evaluaciones' => $total_evaluaciones,
            'total_trivias' => $total_trivias,
            'total_jackpots' => $total_jackpots,
            'fechas' => $array_fechas,
            'sesiones_vis' => $array_visualizaciones,
            'sesiones_eval' => $array_evaluaciones,
            'trivias_res' => $array_trivias,
            'jackpots_intent' => $array_jackpots,
        ];
        return response()->json($completo);
    }

    

    public function agregar_usuario_api(Request $request)
{
    // Verificar si el usuario ya existe
    $lider = User::find($request->id_usuario)->first();
    $usuario = User::where('email', $request->correo)->first();
    $distribuidor = Distribuidor::where('id', $request->id_distribuidor)->first();
    
    if (!$usuario) {
        $usuario = new User();
        
        $usuario->email = $request->correo;

        $emailPrefix = explode('@', $request->correo)[0];
        do {
            $randomNumbers = rand(100, 999);
            $newLegacyId = $emailPrefix . $randomNumbers;
        } while (User::where('legacy_id', $newLegacyId)->exists());
        $usuario->legacy_id = $newLegacyId;

        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->telefono = '';
        $usuario->whatsapp = '';
        $usuario->fecha_nacimiento = null;
        if(!empty($distribuidor->default_pass)){
            $usuario->password = Hash::make($distribuidor->default_pass);
        }else{
            $usuario->password = Hash::make('12345');
        }
        
        $usuario->lista_correo = 'si';
        $usuario->imagen = 'default.jpg';
        $usuario->clase = 'usuario';
        $usuario->estado = 'activo';

        $usuario->save();

        $accion = new AccionesUsuarios;
        $accion->id_usuario = $lider->id;
        $accion->nombre = $lider->nombre.' '.$lider->apellidos;
        $accion->correo = $lider->email;
        $accion->accion = 'registro usuario';
        $accion->descripcion = 'Lider registró el usuario: '.$usuario->nombre.' '.$usuario->apellidos.' | '.$usuario->email;
        $accion->id_cuenta = null;
        $accion->id_temporada = null;
        $accion->funcion = 'lider';
        $accion->save();
    }

    $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', $request->id_temporada)->first();
    if (!$suscripcion) {
        $suscripcion = new UsuariosSuscripciones();
        $suscripcion->id_usuario = $usuario->id;
        $suscripcion->id_cuenta = $request->id_cuenta;
        $suscripcion->id_temporada = $request->id_temporada;
        $suscripcion->id_distribuidor = $request->id_distribuidor;
        $suscripcion->id_sucursal = $request->id_sucursal;
        $suscripcion->confirmacion_puntos = 'pendiente';
        $suscripcion->funcion = 'usuario';
        $suscripcion->save();
        // Registro la acción de suscribir al usuario
        $temporada = Temporada::find($request->id_temporada);
        $cuenta = Cuenta::find($request->id_cuenta);
        $accion = new AccionesUsuarios;
        $accion->id_usuario = $lider->id;
        $accion->nombre = $lider->nombre.' '.$lider->apellidos;
        $accion->correo = $lider->email;
        $accion->accion = 'registro usuario temporada';
        $accion->descripcion = 'Se registró el usuario '.$usuario->email.' en la temporada: '.$cuenta->nombre.' '.$temporada->nombre;
        $accion->id_cuenta = $cuenta->id;
        $accion->id_temporada = $temporada->id;
        $accion->funcion = 'lider';
        $accion->save();
    }
    
    $mensaje = '<p><b>¡Te han seleccionado para participar en PLearning! Anexamos tu nombre de usuario y contraseña</b>, y te invitamos a cambiar esta última tan pronto como ingreses al programa. ¡Que tengas mucho éxito y disfrutes tu participación en esta temporada de PLearning!</p>';
    $mensaje .= '<table>';
    $mensaje .= '<tbody>';
    $mensaje .= '<tr>';
    $mensaje .= '<th>Nombre</th>';
    $mensaje .= '<td>'.$usuario->nombre.' '.$usuario->apellidos.'</td>';
    $mensaje .= '</tr>';
    $mensaje .= '<tr>';
    $mensaje .= '<th>Correo</th>';
    $mensaje .= '<td>'.$usuario->email.'</td>';
    $mensaje .= '</tr>';
    $mensaje .= '<tr>';
    $mensaje .= '<th>Nombre de Usuario</th>';
    $mensaje .= '<td>'.$usuario->legacy_id.'</td>';
    $mensaje .= '</tr>';
    $mensaje .= '<tr>';
    $mensaje .= '<th>Contraseña</th>';
    $mensaje .= '<td>'.$distribuidor->default_pass.'</td>';
    $mensaje .= '</tr>';
    $mensaje .= '</tbody>';
    $mensaje .= '<table>';

    switch ($request->id_cuenta) {
        case '1':
            $data = [
                'titulo' => '¡Bienvenido a PL-Electrico!',
                'contenido' => $mensaje,
                'boton_texto' => 'Entrar',
                'boton_enlace' => 'https://pls-test.panduitlatam.com/'
            ];
            break;

        case '3':
            $data = [
                'titulo' => '¡Bienvenido a P-Learning!',
                'contenido' => $mensaje,
                'boton_texto' => 'Entrar',
                'boton_enlace' => 'https://pl-test.panduitlatam.com/'
            ];
            break;
        
        default:
            $data = [
                'titulo' => '¡Bienvenido a PL-Electrico!',
                'contenido' => $mensaje,
                'boton_texto' => 'Entrar',
                'boton_enlace' => 'https://ple-test.panduitlatam.com/'
            ];
            break;
    }
    
    try {
        Mail::to($usuario->email)->send(new RegistroUsuario($data));
    } catch (\Exception $e) {
        // Registrar el error pero permitir que el programa continúe
        \Log::error('Error al enviar correo de bienvenida: ' . $e->getMessage());
    }
    
    return 'Guardado';
}

    public function actualizar_usuario_api (Request $request)
    {   
            // Verificar si el usuario ya existe
        $suscripcion = UsuariosSuscripciones::where('id', $request->suscripcion)->first();
        $usuario = User::where('id', $suscripcion->id_usuario)->first();
        $lider = User::find($request->id_usuario);
        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->email = $request->correo;
        $usuario->save();

        $accion = new AccionesUsuarios;
        $accion->id_usuario = $lider->id;
        $accion->nombre = $lider->nombre.' '.$lider->apellidos;
        $accion->correo = $lider->email;
        $accion->accion = 'actualizacion usuario';
        $accion->descripcion = 'Como lider se actualizó el usuario: '.$usuario->nombre.' '.$usuario->apellidos.' | '.$usuario->email;
        $accion->id_cuenta = $suscripcion->id_cuenta;
        $accion->id_temporada = $suscripcion->id_temporada;
        $accion->funcion = 'lider';
        $accion->save();
        
        return 'Guardado';
        
    }

    public function panel_lider_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_usuario = $request->input('id_usuario');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
       
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);

        //Gráfica
        $fecha_inicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_final = $request->input('fecha_final') ? Carbon::parse($request->input('fecha_final')) : Carbon::now();

        $fechas_array = [];
        $engagement_visualizaciones = [];
        $engagement_evaluaciones = [];
        $engagement_trivias = [];
        $engagement_jackpots = [];

        for ($fecha = $fecha_inicio; $fecha->lte($fecha_final); $fecha->addDay()) {
            $fechas_array[] = $fecha->toDateString();
            $engagement_visualizaciones[] = (int) SesionVis::where('id_temporada', $id_temporada)->whereDate('fecha_ultimo_video', $fecha->toDateString())->count();
            $engagement_evaluaciones[] = (int) EvaluacionRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_trivias[] = (int) TriviaRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_jackpots[] = (int) JackpotIntentos::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
        }


            $completo = [
                'fechas_array' => $fechas_array,
                'engagement_visualizaciones' => $engagement_visualizaciones,
                'engagement_evaluaciones' => $engagement_evaluaciones,
                'engagement_trivias' => $engagement_trivias,
                'engagement_jackpots' => $engagement_jackpots,
            ];
            return response()->json($completo);


    }

    public function datos_basicos_lider_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_usuario = $request->input('id_usuario');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $sesiones = SesionEv::where('id_temporada', $id_temporada)->count();
        $sesiones_pendientes = SesionEv::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_sesiones = SesionEv::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();
        $trivias = Trivia::where('id_temporada', $id_temporada)->count();
        $trivias_pendientes = Trivia::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_trivias = Trivia::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();
        $jackpots = Jackpot::where('id_temporada', $id_temporada)->count();
        $jackpots_pendientes = Jackpot::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_jackpots = Jackpot::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();

        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);

        $participaciones_logros = LogroParticipacion::where('id_temporada', $id_temporada)->where('id_distribuidor', $distribuidor->id)->count();
        $anexos_logros = 0;
        $productos_logros = 0;
       
        
        $suscriptores = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->where('usuarios_suscripciones.id_distribuidor', '=', $suscripcion->id_distribuidor)
            ->select('usuarios.nombre', 'usuarios.apellidos','usuarios.email', 'usuarios_suscripciones.*')
            ->get();
        $array_suscriptores = array();
        $suscriptores_totales = 0;
        $suscriptores_activos = 0;
        $suscriptores_participantes = 0;
        $array_nombres = array();
        $top_sesiones = array();
        $array_nombres_sesiones = array();
        $top_trivias = array();
        $array_nombres_trivias = array();
        $top_jackpots = array();
        $array_jackpots = array();
        $top_10 = array();
        $no_usuarios_sesiones = 0;
        $no_usuarios_trivias = 0;
        $no_usuarios_jackpots = 0;

        foreach($lista_sesiones as $sesion){
            $conteo_vis = SesionVis::where('id_sesion', $sesion->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->count();
            $conteo_res = EvaluacionRes::where('id_sesion', $sesion->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->distinct('id_usuario')->count();
            $top_sesiones[$sesion->id] =  $conteo_vis;
            $array_nombres_sesiones[$sesion->id] = $sesion->titulo;        }

        foreach($lista_trivias as $trivia){
            $conteo_res = TriviaRes::where('id_trivia', $trivia->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->distinct('id_usuario')->count();
            $top_trivias[$trivia->id] = $conteo_res;
            $array_nombres_trivias[$trivia->id] = $trivia->titulo;
        }
        foreach($lista_jackpots as $jackpot){
            $conteo_res = JackpotIntentos::where('id_jackpot', $jackpot->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->where('puntaje', '>', 0)->count();
            $top_jackpots[$jackpot->id] = $conteo_res;
            $array_nombres_jackpots[$jackpot->id] = $jackpot->titulo;
        }


        foreach($suscriptores as $suscriptor){
            $array_nombres[$suscriptor->id_usuario] =  $suscriptor->nombre.' '.$suscriptor->apellidos;

            //Verifico si están activos
            $activo = false;
            $participante = false;
            $hay_login = Tokens::where('tokenable_id', $suscriptor->id_usuario)->first();
            if($hay_login){ $activo=true; }
            $hay_sesiones = SesionVis::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_sesiones){ $participante=true; }
            $hay_evaluaciones = EvaluacionRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_evaluaciones){ $participante=true; $no_usuarios_sesiones++;}
            $hay_trivias = TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_trivias){ $participante=true; $no_usuarios_trivias++;}
            $hay_jackpot = JackpotIntentos::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_jackpot){ $participante=true; $no_usuarios_jackpots++;}

            if($participante){ $activo=true; }


            // Cálculos de puntaje
            $puntos_sesiones = (int) SesionVis::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_evaluaciones = (int) EvaluacionRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_trivias = (int) TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_jackpot = (int) JackpotIntentos::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_extras = (int) PuntosExtra::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntos');
            $puntos_totales = $puntos_sesiones+$puntos_evaluaciones+$puntos_trivias+$puntos_jackpot+$puntos_extras;
            $top_10[$suscriptor->id_usuario] = $puntos_totales;

            $anexos = LogroAnexo::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->count();
            $productos_anexos = LogroAnexoProducto::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->count();
            
            $anexos_logros += $anexos;
            $productos_logros += $productos_anexos;
            
            $array_suscriptores[$suscriptor->id_usuario] = [ 
                'nombre' => $suscriptor->nombre,
                'apellidos' => $suscriptor->apellidos,
                'email' => $suscriptor->email,
                'suscripcion' => $suscriptor->id,
                'activo' => $activo,
                'participante' => $participante,
                'distribuidor' => $distribuidor->nombre,
                'puntos_sesiones' => $puntos_sesiones,
                'puntos_evaluaciones' => $puntos_evaluaciones,
                'puntos_trivias' => $puntos_trivias,
                'puntos_jackpots' => $puntos_jackpot,
                'puntos_extra' => $puntos_extras,
                'puntos_totales' => $puntos_totales
            ];

            if($activo){ $suscriptores_activos++; }
            if($participante){ $suscriptores_participantes++; }
            $suscriptores_totales ++;
        }
        // ordeno sesiones 
        $top_sesiones_ordenado = array();
        $participaciones_sesiones = 0;
        arsort($top_sesiones);
        foreach($top_sesiones as $id=>$puntos){
            $top_sesiones_ordenado[] = ['id' => $id, 'puntos' => $puntos];
            $participaciones_sesiones +=$puntos;
        }

        // ordeno trivias 
        $top_trivias_ordenado = array();
        $participaciones_trivias = 0;
        arsort($top_trivias);
        foreach($top_trivias as $id=>$puntos){
            $top_trivias_ordenado[] = ['id' => $id, 'puntos' => $puntos];
            $participaciones_trivias +=$puntos;
        }

        $top_jackpots_ordenado = array();
        arsort($top_jackpots);
        $participaciones_jackpots = 0;
        foreach($top_jackpots as $id=>$puntos){
            $top_jackpots_ordenado[] = ['id' => $id, 'puntos' => $puntos];
            $participaciones_jackpots +=$puntos;
        }

        // ordeno top 10
        $top_10_ordenado = array();
        arsort($top_10);
        foreach($top_10 as $id=>$puntos){
            $top_10_ordenado[] = ['id' => $id, 'puntos' => $puntos];
        }

        //Gráfica
        $fecha_inicio = Carbon::now()->subDays(15); // Fecha 15 días atrás
        $fecha_final = Carbon::now(); // Fecha de hoy

        $fechas_array = [];
        $engagement_visualizaciones = [];
        $engagement_evaluaciones = [];
        $engagement_trivias = [];
        $engagement_jackpots = [];

        for ($fecha = $fecha_inicio; $fecha->lte($fecha_final); $fecha->addDay()) {
            $fechas_array[] = $fecha->toDateString();
            $engagement_visualizaciones[] = (int) SesionVis::where('id_temporada', $id_temporada)->whereDate('fecha_ultimo_video', $fecha->toDateString())->count();
            $engagement_evaluaciones[] = (int) EvaluacionRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_trivias[] = (int) TriviaRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_jackpots[] = (int) JackpotIntentos::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
        }


            $completo = [
                'usuario' => $usuario,
                'temporada' => $temporada,
                'suscripcion' => $suscripcion,
                'distribuidor' => $distribuidor,
                'sesiones' => $sesiones,
                'sesiones_pendientes' => $sesiones_pendientes,
                'trivias' => $trivias,
                'trivias_pendientes' => $trivias_pendientes,
                'jackpots' => $jackpots,
                'jackpots_pendientes' => $jackpots_pendientes,
                'suscriptores' => $array_suscriptores,
                'totales' => $suscriptores_totales,
                'activos' => $suscriptores_activos,
                'participantes' => $suscriptores_participantes,
                'array_nombres' => $array_nombres,
                'array_nombres_sesiones' => $array_nombres_sesiones,
                'array_nombres_trivias' => $array_nombres_trivias,
                'top_sesiones' => $top_sesiones_ordenado,
                'top_trivias' => $top_trivias_ordenado,
                'top_jackpots' => $top_trivias_ordenado,
                'top_10' => $top_10_ordenado,
                'no_usuarios_sesiones' => $no_usuarios_sesiones,
                'no_usuarios_trivias' => $no_usuarios_trivias,
                'no_usuarios_jackpots' => $no_usuarios_jackpots,
                'participaciones_sesiones' => $participaciones_sesiones,
                'participaciones_trivias' => $participaciones_trivias,
                'participaciones_jackpots' => $participaciones_jackpots,
                'fechas_array' => $fechas_array,
                'engagement_visualizaciones' => $engagement_visualizaciones,
                'engagement_evaluaciones' => $engagement_evaluaciones,
                'engagement_trivias' => $engagement_trivias,
                'engagement_jackpots' => $engagement_jackpots,
                'no_participaciones_logros' => $participaciones_logros,
                'no_anexos' => $anexos_logros,
                'no_anexos_productos' => $productos_logros,
            ];
            return response()->json($completo);


    }

    public function eliminar_usuario_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_temporada = $request->input('id_temporada');
        $id_suscripcion = $request->input('id_suscripcion');
        $cuenta = Cuenta::find($id_cuenta);
        $temporada = Temporada::find($id_cuenta);
        $suscripcion = UsuariosSuscripciones::find($id_suscripcion);
        $usuario = User::find($suscripcion->id_usuario);
        $lider = User::find($request->input('id_usuario'));
        
        /*
        $visualizaciones = SesionVis::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $evaluaciones = EvaluacionRes::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $trivias = TriviaRes::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $trivias = TriviaGanador::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $jackpot_respuestas = JackpotRes::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        $jackpot_intentos = JackpotIntentos::where('id_usuario',$usuario->id)->where('id_temporada', $id_temporada)->delete();
        */
        $suscripcion->delete();

        $accion = new AccionesUsuarios();
        $accion->id_usuario = $lider->id;
        $accion->nombre = $lider->nombre.''.$lider->apellidos;
        $accion->correo = $lider->email;
        $accion->accion = 'suscripcion_borrada';
        $accion->descripcion = $usuario->nombre.' '.$usuario->apellidos.' | '.$usuario->email.'Fue eliminado de la temporada: '.$cuenta->nombre.' '.$temporada->nombre.' por un lider';
        $accion->id_cuenta = $id_cuenta;
        $accion->id_temporada = $id_temporada;
        $accion->funcion = 'lider';
        $accion->save();

        return 'Eliminado';
        
       
    }

    public function actualizar_usuario_perfil_api(Request $request)
{
    // Validaciones básicas para los campos de texto (ajústalas si necesitas)
    $request->validate([
        'id_usuario' => 'required|integer|exists:usuarios,id',
        'legacy_id' => 'nullable|string',
        'telefono' => 'nullable|string',
        'fecha_nacimiento' => 'required|date',
    ]);

    $usuario = User::find($request->id_usuario);
    $suscripcion = UsuariosSuscripciones::where('id', $request->id_suscripcion)->first();
    if ($usuario) {
        $usuario->legacy_id = $request->legacy_id;
        $usuario->telefono = $request->telefono ?? ''; // si viene vacío, lo limpia
        $usuario->fecha_nacimiento = date('Y-m-d', strtotime($request->fecha_nacimiento));
        $usuario->save();

        // Registro de acción
        $accion = new AccionesUsuarios();
        $accion->id_usuario = $usuario->id;
        $accion->nombre = $usuario->nombre . ' ' . $usuario->apellidos;
        $accion->correo = $usuario->email;
        $accion->accion = 'actualizacion_datos';
        $accion->descripcion = 'Actualizó su perfil';
        $accion->id_cuenta = $suscripcion->id_cuenta ? $suscripcion->id_cuenta : null;
        $accion->id_temporada = $suscripcion->id_temporada ? $suscripcion->id_temporada : null;
        $accion->funcion = 'usuario';
        $accion->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Guardado',
            'usuario' => $usuario
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'No hay usuario: ' . $request->id_usuario
        ],404);
    }
}

    public function actualizar_imagen_perfil_api (Request $request)
    {
            // Verificar si el usuario ya existe

            $request->validate([
                'photo' => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:10048' // Ajusta las reglas de validación según tus necesidades}
            ]);
            $subido = false;
            if ($request->hasFile('photo')) {
                $archivo = $request->file('photo');
                $nombreArchivo = 'photo'.time().'.'.$archivo->extension();
                if($archivo->move(base_path('../public_html/img/usuarios'), $nombreArchivo)){
                    $subido = true;
                }
            }
            
           

        $usuario = User::find($request->id_usuario);
        $suscripcion = UsuariosSuscripciones::where('id', $request->id_suscripcion)->first();
        if($usuario){
            if ($subido) {
                $usuario->imagen = $nombreArchivo;
                $usuario->save();
                // Registro la acción 
                $accion = new AccionesUsuarios;
                $accion->id_usuario = $usuario->id;
                $accion->nombre = $usuario->nombre.' '.$usuario->apellidos;
                $accion->correo = $usuario->email;
                $accion->accion = 'actualizacion imagen';
                $accion->descripcion = 'Actualizó su imágen de perfil';
                $accion->id_cuenta = $suscripcion->id_cuenta;
                $accion->id_temporada = $suscripcion->id_teporada;
                $accion->funcion = 'usuario';
                $accion->save();
            }
            
            
            if ($subido) {
                return 'Guardado con foto';
            }else{
                return 'Guardado sin foto';
            }
        }else{
            return 'No hay usuario: '.$request->id_usuario;
        }
        
        
    }

    public function actualizar_pass_perfil_api(Request $request)
    {
        $id_usuario = $request->id_usuario;
        $old_pass = $request->old_pass;
        $new_pass = $request->new_pass;
        $confirm_pass = $request->confirm_pass;

        $usuario = User::find($id_usuario);

        if (!$usuario) {
            return 'user';
        }

        if (!Hash::check($old_pass, $usuario->password)) {
            return 'old_pass';
        }

        if ($new_pass !== $confirm_pass) {
            return 'new_pass';
        }

        // Actualizar contraseña
        $usuario->password = Hash::make($new_pass);
        $usuario->save();

        // Registrar acción
        $accion = new AccionesUsuarios();
        $accion->id_usuario = $usuario->id;
        $accion->nombre = $usuario->nombre . ' ' . $usuario->apellidos;
        $accion->correo = $usuario->email;
        $accion->accion = 'actualizacion_pass';
        $accion->descripcion = 'Actualizó su contraseña';
        $accion->save();

        // Enviar correo (sin detener el flujo si falla)
        try {
            $data = [
                'titulo' => 'Tu contraseña de PLearning ha sido cambiada',
                'contenido' => '<p>Tu contraseña para ingresar al sitio de PLearning fue actualizada con éxito; de ahora en adelante debes usar sólo tu nueva contraseña. Si no solicitaste el cambio de contraseña, comunícate inmediatamente con nosotros para informar de un problema de seguridad.</p>
                                <p>Ésta es tu nueva contraseña:</p>
                                <p>' . $new_pass . '</p>
                                <p>No compartas ni reveles tu contraseña a nadie.</p>',
                'boton_texto' => 'ENTRAR AHORA',
                'boton_enlace' => 'https://pl-electrico.panduitlatam.com/login',
                'newpass' => $new_pass,
            ];

            Mail::to($usuario->email)->send(new CambioPass($data));
        } catch (\Exception $e) {
            // Opcional: puedes registrar el error en logs si deseas
            \Log::error('Error al enviar correo de cambio de contraseña: ' . $e->getMessage());
        }

        return 'ok';
    }

    public function registro_clic_noticia_api(Request $request)
{
    $usuario = User::find($request->id_usuario);
    $noticia = Publicacion::find($request->id_noticia);

    if (!$usuario || !$noticia) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuario o noticia no encontrados',
        ], 404);
    }

    $accion = new AccionesUsuarios();
    $accion->id_usuario = $usuario->id;
    $accion->nombre = $usuario->nombre . ' ' . $usuario->apellidos;
    $accion->correo = $usuario->email;
    $accion->accion = 'click en noticia';
    $accion->descripcion = 'Se dio click en la noticia id: ' . $noticia->id . ' título: ' . $noticia->titulo;
    $accion->id_cuenta = $noticia->id_cuenta;
    $accion->id_temporada = $noticia->id_temporada;
    $accion->funcion = 'usuario';
    $accion->save();

    return response()->json([
        'status' => 'ok',
        'message' => 'Registro guardado correctamente',
    ]);
}

    /**
     * API SUPER LIDER
     */
    public function distribuidores_super_lider_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_usuario = $request->input('id_usuario');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $idDistribuidores = UsuariosSuscripciones::where('id_temporada', $id_temporada)
            ->distinct()
            ->pluck('id_distribuidor');
       
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
        $region = $request->input('region') ?? $suscripcion->funcion_region;
         $distribuidores = Distribuidor::where('region', $region)
        ->whereIn('id', $idDistribuidores)
        ->get();


        $completo = [
            'usuario' => $usuario,
            'suscripcion' => $suscripcion,
            'temporada' => $temporada,
            'distribuidor' => $distribuidor,
            'distribuidores' => $distribuidores,
            'region' => $region,
        ];
        return response()->json($completo);


    }
    
    public function panel_super_lider_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_usuario = $request->input('id_usuario');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
       
        $distribuidor = Distribuidor::find($request->input('id_distribuidor'));

        //Gráfica
        $fecha_inicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_final = $request->input('fecha_final') ? Carbon::parse($request->input('fecha_final')) : Carbon::now();

        $fechas_array = [];
        $engagement_visualizaciones = [];
        $engagement_evaluaciones = [];
        $engagement_trivias = [];
        $engagement_jackpots = [];

        for ($fecha = $fecha_inicio; $fecha->lte($fecha_final); $fecha->addDay()) {
            $fechas_array[] = $fecha->toDateString();
            $engagement_visualizaciones[] = (int) SesionVis::where('id_temporada', $id_temporada)->whereDate('fecha_ultimo_video', $fecha->toDateString())->count();
            $engagement_evaluaciones[] = (int) EvaluacionRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_trivias[] = (int) TriviaRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_jackpots[] = (int) JackpotIntentos::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
        }


            $completo = [
                'fechas_array' => $fechas_array,
                'engagement_visualizaciones' => $engagement_visualizaciones,
                'engagement_evaluaciones' => $engagement_evaluaciones,
                'engagement_trivias' => $engagement_trivias,
                'engagement_jackpots' => $engagement_jackpots,
            ];
            return response()->json($completo);


    }

    public function datos_basicos_super_lider_api (Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_usuario = $request->input('id_usuario');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $distribuidor = Distribuidor::find( $request->input('id_distribuidor'));
        $sesiones = SesionEv::where('id_temporada', $id_temporada)->count();
        $sesiones_pendientes = SesionEv::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_sesiones = SesionEv::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();
        $trivias = Trivia::where('id_temporada', $id_temporada)->count();
        $trivias_pendientes = Trivia::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_trivias = Trivia::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();
        $jackpots = Jackpot::where('id_temporada', $id_temporada)->count();
        $jackpots_pendientes = Jackpot::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '>', now())->count();
        $lista_jackpots = Jackpot::where('id_temporada', $id_temporada)->whereDate('fecha_publicacion', '<=', now())->get();
        
        $participaciones_logros = LogroParticipacion::where('id_temporada', $id_temporada)->where('id_distribuidor', $distribuidor->id)->count();
        $anexos_logros = 0;
        $productos_logros = 0;
       
        
        $suscriptores = DB::table('usuarios_suscripciones')
            ->join('usuarios', 'usuarios_suscripciones.id_usuario', '=', 'usuarios.id')
            ->where('usuarios_suscripciones.id_temporada', '=', $id_temporada)
            ->where('usuarios_suscripciones.id_distribuidor', '=', $distribuidor->id)
            ->select('usuarios.nombre', 'usuarios.apellidos','usuarios.email', 'usuarios_suscripciones.*')
            ->get();
        $array_suscriptores = array();
        $suscriptores_totales = 0;
        $suscriptores_activos = 0;
        $suscriptores_participantes = 0;
        $array_nombres = array();
        $top_sesiones = array();
        $array_nombres_sesiones = array();
        $top_trivias = array();
        $array_nombres_trivias = array();
        $top_jackpots = array();
        $array_jackpots = array();
        $top_10 = array();
        $no_usuarios_sesiones = 0;
        $no_usuarios_trivias = 0;
        $no_usuarios_jackpots = 0;

        foreach($lista_sesiones as $sesion){
            $conteo_vis = SesionVis::where('id_sesion', $sesion->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->count();
            $conteo_res = EvaluacionRes::where('id_sesion', $sesion->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->distinct('id_usuario')->count();
            $top_sesiones[$sesion->id] =  $conteo_vis;
            $array_nombres_sesiones[$sesion->id] = $sesion->titulo;        }

        foreach($lista_trivias as $trivia){
            $conteo_res = TriviaRes::where('id_trivia', $trivia->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->distinct('id_usuario')->count();
            $top_trivias[$trivia->id] = $conteo_res;
            $array_nombres_trivias[$trivia->id] = $trivia->titulo;
        }
        foreach($lista_jackpots as $jackpot){
            $conteo_res = JackpotIntentos::where('id_jackpot', $jackpot->id)->where('id_distribuidor', $suscripcion->id_distribuidor)->where('puntaje', '>', 0)->count();
            $top_jackpots[$jackpot->id] = $conteo_res;
            $array_nombres_jackpots[$jackpot->id] = $jackpot->titulo;
        }


        foreach($suscriptores as $suscriptor){
            $array_nombres[$suscriptor->id_usuario] =  $suscriptor->nombre.' '.$suscriptor->apellidos;

            //Verifico si están activos
            $activo = false;
            $participante = false;
            $hay_login = Tokens::where('tokenable_id', $suscriptor->id_usuario)->first();
            if($hay_login){ $activo=true; }
            $hay_sesiones = SesionVis::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_sesiones){ $participante=true; }
            $hay_evaluaciones = EvaluacionRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_evaluaciones){ $participante=true; $no_usuarios_sesiones++;}
            $hay_trivias = TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_trivias){ $participante=true; $no_usuarios_trivias++;}
            $hay_jackpot = JackpotIntentos::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->first();
            if($hay_jackpot){ $participante=true; $no_usuarios_jackpots++;}

            if($participante){ $activo=true; }


            // Cálculos de puntaje
            $puntos_sesiones = (int) SesionVis::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_evaluaciones = (int) EvaluacionRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_trivias = (int) TriviaRes::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_jackpot = (int) JackpotIntentos::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntaje');
            $puntos_extras = (int) PuntosExtra::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->sum('puntos');
            $puntos_totales = $puntos_sesiones+$puntos_evaluaciones+$puntos_trivias+$puntos_jackpot+$puntos_extras;
            $top_10[$suscriptor->id_usuario] = $puntos_totales;

            $anexos = LogroAnexo::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->count();
            $productos_anexos = LogroAnexoProducto::where('id_temporada', $id_temporada)->where('id_usuario', $suscriptor->id_usuario)->count();
            
            $anexos_logros += $anexos;
            $productos_logros += $productos_anexos;
            
            
            $array_suscriptores[$suscriptor->id_usuario] = [ 
                'nombre' => $suscriptor->nombre,
                'apellidos' => $suscriptor->apellidos,
                'email' => $suscriptor->email,
                'suscripcion' => $suscriptor->id,
                'activo' => $activo,
                'participante' => $participante,
                'distribuidor' => $distribuidor->nombre,
                'puntos_sesiones' => $puntos_sesiones,
                'puntos_evaluaciones' => $puntos_evaluaciones,
                'puntos_trivias' => $puntos_trivias,
                'puntos_jackpots' => $puntos_jackpot,
                'puntos_extra' => $puntos_extras,
                'puntos_totales' => $puntos_totales
            ];

            if($activo){ $suscriptores_activos++; }
            if($participante){ $suscriptores_participantes++; }
            $suscriptores_totales ++;
        }
        // ordeno sesiones 
        $top_sesiones_ordenado = array();
        $participaciones_sesiones = 0;
        arsort($top_sesiones);
        foreach($top_sesiones as $id=>$puntos){
            $top_sesiones_ordenado[] = ['id' => $id, 'puntos' => $puntos];
            $participaciones_sesiones +=$puntos;
        }

        // ordeno trivias 
        $top_trivias_ordenado = array();
        $participaciones_trivias = 0;
        arsort($top_trivias);
        foreach($top_trivias as $id=>$puntos){
            $top_trivias_ordenado[] = ['id' => $id, 'puntos' => $puntos];
            $participaciones_trivias +=$puntos;
        }

        $top_jackpots_ordenado = array();
        arsort($top_jackpots);
        $participaciones_jackpots = 0;
        foreach($top_jackpots as $id=>$puntos){
            $top_jackpots_ordenado[] = ['id' => $id, 'puntos' => $puntos];
            $participaciones_jackpots +=$puntos;
        }

        // ordeno top 10
        $top_10_ordenado = array();
        arsort($top_10);
        foreach($top_10 as $id=>$puntos){
            $top_10_ordenado[] = ['id' => $id, 'puntos' => $puntos];
        }

        //Gráfica
        $fecha_inicio = Carbon::now()->subDays(15); // Fecha 15 días atrás
        $fecha_final = Carbon::now(); // Fecha de hoy

        $fechas_array = [];
        $engagement_visualizaciones = [];
        $engagement_evaluaciones = [];
        $engagement_trivias = [];
        $engagement_jackpots = [];

        for ($fecha = $fecha_inicio; $fecha->lte($fecha_final); $fecha->addDay()) {
            $fechas_array[] = $fecha->toDateString();
            $engagement_visualizaciones[] = (int) SesionVis::where('id_temporada', $id_temporada)->whereDate('fecha_ultimo_video', $fecha->toDateString())->count();
            $engagement_evaluaciones[] = (int) EvaluacionRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_trivias[] = (int) TriviaRes::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
            $engagement_jackpots[] = (int) JackpotIntentos::where('id_temporada', $id_temporada)->whereDate('fecha_registro', $fecha->toDateString())->count();
        }


            $completo = [
                'usuario' => $usuario,
                'temporada' => $temporada,
                'suscripcion' => $suscripcion,
                'distribuidor' => $distribuidor,
                'sesiones' => $sesiones,
                'sesiones_pendientes' => $sesiones_pendientes,
                'trivias' => $trivias,
                'trivias_pendientes' => $trivias_pendientes,
                'jackpots' => $jackpots,
                'jackpots_pendientes' => $jackpots_pendientes,
                'suscriptores' => $array_suscriptores,
                'totales' => $suscriptores_totales,
                'activos' => $suscriptores_activos,
                'participantes' => $suscriptores_participantes,
                'array_nombres' => $array_nombres,
                'array_nombres_sesiones' => $array_nombres_sesiones,
                'array_nombres_trivias' => $array_nombres_trivias,
                'top_sesiones' => $top_sesiones_ordenado,
                'top_trivias' => $top_trivias_ordenado,
                'top_jackpots' => $top_trivias_ordenado,
                'top_10' => $top_10_ordenado,
                'no_usuarios_sesiones' => $no_usuarios_sesiones,
                'no_usuarios_trivias' => $no_usuarios_trivias,
                'no_usuarios_jackpots' => $no_usuarios_jackpots,
                'participaciones_sesiones' => $participaciones_sesiones,
                'participaciones_trivias' => $participaciones_trivias,
                'participaciones_jackpots' => $participaciones_jackpots,
                'fechas_array' => $fechas_array,
                'engagement_visualizaciones' => $engagement_visualizaciones,
                'engagement_evaluaciones' => $engagement_evaluaciones,
                'engagement_trivias' => $engagement_trivias,
                'engagement_jackpots' => $engagement_jackpots,
                'no_participaciones_logros' => $participaciones_logros,
                'no_anexos' => $anexos_logros,
                'no_anexos_productos' => $productos_logros,
            ];
            return response()->json($completo);


    }

    public function datos_champions_super_lider_api(Request $request)
    {
        $id_cuenta = $request->input('id_cuenta');
        $id_usuario = $request->input('id_usuario');
        $id_distribuidor = $request->input('id_distribuidor');

        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;

        $usuario = User::find($id_usuario);
        $temporada = Temporada::find($id_temporada);

        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)
            ->where('id_usuario', $id_usuario)
            ->first();

        $distribuidor = Distribuidor::find($id_distribuidor);

        $lista_logros = Logro::where('id_temporada', $id_temporada)
            ->where(function ($query) use ($distribuidor) {
                $query->whereNull('id_distribuidor')
                    ->orWhere('id_distribuidor', '')
                    ->orWhere('id_distribuidor', $distribuidor->id);
            })
            ->with('participaciones')
            ->get();

        foreach ($lista_logros as $logro) {
            $participaciones = $logro->participaciones;

            $cantidad_participantes = $participaciones->whereIn('estado', ['participante', 'validando'])->where('id_distribuidor', $distribuidor->id)->count();
            $cantidad_finalizados = $participaciones->where('estado', 'finalizado')->where('id_distribuidor', $distribuidor->id)->count();

            $total_a = $participaciones->where('confirmacion_nivel_a', 'si')->where('id_distribuidor', $distribuidor->id)->count();
            $total_b = $participaciones->where('confirmacion_nivel_b', 'si')->where('id_distribuidor', $distribuidor->id)->count();
            $total_c = $participaciones->where('confirmacion_nivel_c', 'si')->where('id_distribuidor', $distribuidor->id)->count();
            $total_especial = $participaciones->where('confirmacion_nivel_d', 'si')->where('id_distribuidor', $distribuidor->id)->count();

            if ($distribuidor->region == 'RoLA') {
                $total_acumulado = 
                    ($logro->premio_rola_a * $total_a) +
                    ($logro->premio_rola_b * $total_b) +
                    ($logro->premio_rola_c * $total_c) +
                    ($logro->premio_rola_especial * $total_especial);
            } else {
                $total_acumulado = 
                    ($logro->premio_a * $total_a) +
                    ($logro->premio_b * $total_b) +
                    ($logro->premio_c * $total_c) +
                    ($logro->premio_especial * $total_especial);
            }

            $logro->cantidad_participantes = $cantidad_participantes;
            $logro->cantidad_finalizados = $cantidad_finalizados;
            $logro->total_a = $total_a;
            $logro->total_b = $total_b;
            $logro->total_c = $total_c;
            $logro->total_especial = $total_especial;
            $logro->total_acumulado = $total_acumulado;
        }

        return response()->json([
            'distribuidor' => $distribuidor,
            'logros' => $lista_logros
        ]);
    }

}
