<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
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
use App\Models\User;
use App\Models\Logro;
use App\Models\LogroParticipacion;
use App\Models\CanjeoCortes;
use App\Models\CanjeoCortesUsuarios;
use App\Models\CanjeoProductos;
use App\Models\CanjeoProductosGaleria;
use App\Models\CanjeoTransacciones;
use App\Models\CanjeoTransaccionesProductos;
use Illuminate\Support\Facades\DB;


use App\Mail\ConfirmacionCanje;
use App\Mail\ConfirmacionCanjeUsuario;
use Illuminate\Support\Facades\Mail;

use App\Exports\ReporteTemporadaExport;
use App\Exports\CorteUsuariosExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class CanjeoController extends Controller
{
    //Productos
    public function productos(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $productos = CanjeoProductos::where('id_temporada', $id_temporada)->get();
        return view('admin/canjeo_productos', compact('productos', 'temporada'));
    }

    public function productos_crear(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        return view('admin/canjeo_productos_crear', compact('temporada'));
    }

    public function productos_guardar(Request $request)
    {
        //
        $variaciones = json_encode($request->input('Variaciones'));
        $variaciones_cantidad = json_encode($request->input('VariacionesCantidad'));
        
        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'producto_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = 'producto_default.jpg';
        }

        $producto = new CanjeoProductos();

        $producto->id_temporada = $request->input('IdTemporada');
        $producto->nombre = $request->input('Nombre');
        $producto->descripcion = $request->input('Descripcion');
        $producto->contenido = $request->input('Contenido');
        $producto->variaciones = $variaciones;
         $producto->variaciones_cantidad = $variaciones_cantidad;
        $producto->imagen = $nombreImagen ;
        $producto->creditos = $request->input('Creditos');
        $producto->limite_total = $request->input('LimiteTotal');
        $producto->limite_usuario = $request->input('LimiteUsuario');
        $producto->save();

        return redirect()->route('canjeo.productos', ['id_temporada' => $request->input('IdTemporada')]);
    }
    public function productos_editar(Request $request, string $id)
    {
        //
        $producto = CanjeoProductos::find($id);
        $canjeados = CanjeoTransaccionesProductos::where('id_producto', $producto->id)->count();
        $id_temporada = $producto->id_temporada;
        $temporada = Temporada::find($id_temporada);
        $galeria = CanjeoProductosGaleria::where('id_producto', $id)->orderBy('orden')->get();
        return view('admin/canjeo_productos_editar', compact('producto', 'temporada', 'galeria', 'canjeados'));
    }
    public function productos_actualizar(Request $request, string $id)
    {
        //
        //
        $variaciones = json_encode($request->input('Variaciones'));
        $variaciones_cantidad = json_encode($request->input('VariacionesCantidad'));
        $producto = CanjeoProductos::find($id);
        
        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Ajusta las reglas de validación según tus necesidades
            ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'producto_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $producto->imagen;
        }

        $producto->id_temporada = $request->input('IdTemporada');
        $producto->nombre = $request->input('Nombre');
        $producto->descripcion = $request->input('Descripcion');
        $producto->contenido = $request->input('Contenido');
        $producto->variaciones = $variaciones;
        $producto->variaciones_cantidad = $variaciones_cantidad;
        $producto->imagen = $nombreImagen ;
        $producto->creditos = $request->input('Creditos');
        $producto->limite_total = $request->input('LimiteTotal');
        $producto->limite_usuario = $request->input('LimiteUsuario');
        $producto->save();

        return redirect()->route('canjeo.productos', ['id_temporada' => $request->input('IdTemporada')]);
    }
    public function productos_borrar(string $id)
    {
        //
        $producto = CanjeoProductos::find($id);
        $id_temporada =  $producto->id_temporada;
        
        $producto->delete();
        return redirect()->route('canjeo.productos', ['id_temporada' => $id_temporada]);
    }
    

    // Galeria
    public function productos_galeria_guardar(Request $request)
    {
        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
            ]);

        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'producto_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/plsystem/img/publicaciones'), $nombreImagen);

            $galeria = new CanjeoProductosGaleria();

            $galeria->id_producto = $request->input('IdProducto');
            $galeria->imagen = $nombreImagen ;
            $galeria->save();
        }

        

        return redirect()->route('canjeo.productos_editar', $request->input('IdProducto'));
    }

    public function productos_galeria_reordenar(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            $galeriaItem = CanjeoProductosGaleria::find($item['id']);
            if ($galeriaItem) {
                $galeriaItem->orden = $item['position'];
                $galeriaItem->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function productos_galeria_borrar(string $id)
    {
        //
        $galeria = CanjeoProductosGaleria::find($id);
        $producto = CanjeoProductos::find($galeria->id_producto);
        $id_temporada =  $producto->id_temporada;
        
        $galeria->delete();
        return redirect()->route('canjeo.productos_editar', $producto->id);
    }


    

    // Cortes
    public function cortes(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $cortes = CanjeoCortes::where('id_temporada', $id_temporada)->get();
        $cortes_usuarios = CanjeoCortesUsuarios::where('id_temporada', $id_temporada)->get();
        //$transacciones = CanjeoTransacciones::where('id_temporada', $id_temporada)->get();
        $usuarios = User::all();

        foreach($cortes_usuarios as $cort_usuario){
            $corte = $cortes->firstWhere('id', $cort_usuario->id_corte);
            $transacciones = CanjeoTransacciones::where('id_temporada', $id_temporada)->where('id_corte', $corte->id)->where('id_usuario', $cort_usuario->id_usuario)->get();
            $visualizaciones = SesionVis::where('id_usuario',$cort_usuario->id_usuario)
                            ->where('id_temporada',$cort_usuario->id_temporada)
                            ->where('fecha_ultimo_video', '>=', $corte->fecha_inicio)
                            ->where('fecha_ultimo_video', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                $evaluaciones = EvaluacionRes::where('id_usuario',$cort_usuario->id_usuario)
                            ->where('id_temporada',$cort_usuario->id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                $trivia = TriviaRes::where('id_usuario',$cort_usuario->id_usuario)
                    ->where('id_temporada',$cort_usuario->id_temporada)
                    ->where('fecha_registro', '>=', $corte->fecha_inicio)
                    ->where('fecha_registro', '<=', $corte->fecha_final)
                    ->pluck('puntaje')->sum();
                $jackpots = JackpotIntentos::where('id_usuario',$cort_usuario->id_usuario)
                            ->where('id_temporada',$cort_usuario->id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                $extra = PuntosExtra::where('id_usuario',$cort_usuario->id_usuario)
                            ->where('id_temporada',$cort_usuario->id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntos')->sum();
                $puntaje_corte = $visualizaciones+$evaluaciones+$trivia+$jackpots+$extra;
            
            // Descomentar si se requiere verificar el corte de puntos
            /*
            if($cort_usuario->puntaje != $puntaje_corte){
                $cort_usuario->puntaje = $puntaje_corte;
                $cort_usuario->creditos = $puntaje_corte;
                $cort_usuario->save();
            }
                */
                
            $cort_usuario->puntos_al_corte = $puntaje_corte;
            if($transacciones){
                $cort_usuario->transacciones = $transacciones;
                foreach($transacciones as $transaccion){
                    $productos = CanjeoTransaccionesProductos::where('id_transacciones', $transaccion->id)->get();
                    $transaccion->productos = $productos;
                }
            }else{
                $cort_usuario->transacciones = null;
            }
           
        }

        
        return view('admin/canjeo_cortes', compact('temporada', 'cortes', 'cortes_usuarios', 'usuarios'));
    }

    public function cortes_guardar(Request $request)
    {
        //
        $corte = new CanjeoCortes();

        $corte->id_temporada = $request->input('IdTemporada');
        $corte->titulo = $request->input('Titulo');
        $corte->fecha_inicio = $request->input('FechaInicio');
        $corte->fecha_final = $request->input('FechaFinal');
        $corte->fecha_publicacion_inicio = $request->input('FechaPublicacionInicio');
        $corte->fecha_publicacion_final = $request->input('FechaPublicacionFinal');
        $corte->save();

        return redirect()->route('canjeo.cortes', ['id_temporada' => $request->input('IdTemporada')]);
    }
    public function cortes_actualizar(Request $request, string $id)
    {
        //
    }

    public function cortes_usuario_actualizar(Request $request, string $id)
    {
        //
        $corte_usuario = CanjeoCortesUsuarios::find($id);
        $corte_usuario->puntaje = $request->input('Puntaje');
        $corte_usuario->creditos = $request->input('Puntaje');
        $corte_usuario->save();
        return redirect()->route('canjeo.cortes', ['id_temporada' => $request->input('IdTemporada')]);
    }

    public function cortes_usuario_borrar(Request $request, string $id)
    {
        //
        $corte_usuario = CanjeoCortesUsuarios::find($id);
        $transacciones = CanjeoTransacciones::where('id_corte', $corte_usuario->id_corte)->where('id_usuario', $corte_usuario->id_usuario)->get();
        foreach($transacciones as $transaccion){
            $productos = CanjeoTransaccionesProductos::where('id_transacciones', $transaccion->id)->get();
            foreach($productos as $producto){
                $producto->delete();
            }
            $transaccion->delete();
        }
        $corte_usuario->delete();
        return redirect()->route('canjeo.cortes', ['id_temporada' => $request->input('IdTemporada')]);
    }
    public function cortes_borrar(string $id)
    {
        //
    }

    



    // Transacciones
    public function transacciones(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $id_corte = $request->input('id_corte');
        $temporada = Temporada::find($id_temporada);
        $corte = CanjeoCortes::find($id_corte);
        $transacciones = CanjeoTransacciones::where('id_temporada', $id_temporada)->where('id_corte', $id_corte)->get();
        return view('admin/canjeo_transacciones', compact('transacciones', 'temporada', 'corte'));
    }

    public function transacciones_usuario(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $id_corte = $request->input('id_corte');
        $id_usuario = $request->input('id_usuario');
        $temporada = Temporada::find($id_temporada);
        $corte = CanjeoCortes::find($id_corte);
        $usuario = User::find($id_usuario);
        $transacciones = CanjeoTransacciones::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->where('id_corte', $id_corte)->get();
        foreach ($transacciones as $transaccion) {
            // Obtener los productos relacionados con la transacción actual
            $transacciones_productos = CanjeoTransaccionesProductos::where('id_transacciones', $transaccion->id)->get();
            
            // Agregar los productos a un nuevo atributo 'productos'
            $transaccion->productos = $transacciones_productos;
        }
        return view('admin/canjeo_transacciones_usuario', compact('transacciones', 'temporada', 'corte', 'usuario'));
    }

    // Transacciones
    public function detalle_transaccion(Request $request)
    {
        //
        $id_transaccion = $request->input('id_transaccion');
        $transaccion = CanjeoTransacciones::find($id_transaccion);
        $corte = CanjeoCortes::find($transaccion->id_corte);
        $temporada = Temporada::find($transaccion->id_temporada);
        $usuario = User::find($transaccion->id_usuario);
        $productos_completos = CanjeoProductos::where('id_temporada', $id_temporada)->get();
        $productos_transaccion = CanjeoTransaccionesProductos::where('id_transaccion', $id_transaccion)->get();
        return view('admin/canjeo_detalle_transaccion', compact('transaccion',
        'corte',
        'temporada',
        'usuario',
        'productos_completos',
        'productos_transaccion'));
    }

    public function actualizar_transaccion(Request $request, string $id)
    {
        //
    }

    /**
     * Funciones de EXCEL
     */

     public function exportar_corte (Request $request)
    {
        return Excel::download(new CorteUsuariosExport($request), 'reporte_ventana_canje.xlsx');
        
    }

    /**
     * Funciones API
     */

    public function canje_inicio_api (Request $request){
        //Variables
        $fecha_actual = Carbon::now();
        $id_cuenta =  $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada =  $cuenta->temporada_actual;
        $id_usuario =  $request->input('id_usuario');
        $usuario = User::find($id_usuario);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $id_temporada)->where('id_usuario', $id_usuario)->first();
        $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
        $distribuidor_nombre = $distribuidor->nombre;
        //$prueba =  $request->input('prueba');
        $prueba = 'no';

        // consulta productos
        $productos = CanjeoProductos::where('id_temporada', $id_temporada)->get();

        // Si está activa la prueba
        if($prueba=='si'){
            // Solo obtengo el corte si es un usuario de Rocky o Panduit
            if($distribuidor_nombre == 'Rocky' || $distribuidor_nombre == 'Panduit'|| $distribuidor_nombre == 'Rocky Creativo'){
                $corte = CanjeoCortes::where('id_temporada', $id_temporada)
                    ->where('fecha_publicacion_inicio', '<=', $fecha_actual)
                    ->where('fecha_publicacion_final', '>', $fecha_actual)
                    ->first();
            }
            
        }else{
            // Busco el corte sin importar el distribuidor
            $corte = CanjeoCortes::where('id_temporada', $id_temporada)
                    ->where('fecha_publicacion_inicio', '<=', $fecha_actual)
                    ->where('fecha_publicacion_final', '>', $fecha_actual)
                    ->first();
        }
        
        // Si no hay corte
        if(!$corte){
            // Reviso el corte anterior
            $corte_anterior = CanjeoCortes::where('id_temporada', $id_temporada)
                    ->where('fecha_final', '<=', $fecha_actual)
                    ->first();
            $datos_corte = null;
            $datos_corte_usuario = null;

            // Obtengo los puntos de visualización
                $visualizaciones = SesionVis::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_ultimo_video', '>=', $corte_anterior->fecha_inicio)
                            ->where('fecha_ultimo_video', '<=', $corte_anterior->fecha_final)
                            ->pluck('puntaje')->sum();
            // Obtengo los puntos de evaluación
                $evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte_anterior->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte_anterior->fecha_final)
                            ->pluck('puntaje')->sum();
            // Obtengo los puntos de trivia
                $trivia = TriviaRes::where('id_usuario',$id_usuario)
                    ->where('id_temporada',$id_temporada)
                    ->where('fecha_registro', '>=', $corte_anterior->fecha_inicio)
                    ->where('fecha_registro', '<=', $corte_anterior->fecha_final)
                    ->pluck('puntaje')->sum();
            // Obtengo los puntos de jackpot
                $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte_anterior->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte_anterior->fecha_final)
                            ->pluck('puntaje')->sum();
            // Obtengo los puntos extra
                $extra = PuntosExtra::where('id_usuario',$id_usuario)
                    ->where('id_temporada',$id_temporada)
                    ->where('fecha_registro', '>=', $corte_anterior->fecha_inicio)
                    ->where('fecha_registro', '<=', $corte_anterior->fecha_final)
                    ->pluck('puntos')->sum();
            // Hago la suma total
                $puntaje_total = $visualizaciones+$evaluaciones+$trivia+$jackpots+$extra;
            $creditos_total = 0;
            $creditos_consumidos = 0;
        }else{
            // Si hay corte hago lo siguiente
            // Vacio los datos en la variable datos_corte
            $datos_corte=$corte;
            // Busco el corte del usuario
            $corte_usuario = CanjeoCortesUsuarios::where('id_corte', $corte->id)
            ->where('id_usuario', $id_usuario)
            ->first();

            // Si no hay corte del usuario lo genero
            if(!$corte_usuario){

                // Creo el nuevo corte del usuario
                $corte_usuario = new CanjeoCortesUsuarios();
                $corte_usuario->id_corte = $corte->id;
                $corte_usuario->id_temporada = $id_temporada;
                $corte_usuario->id_usuario = $id_usuario;
                // Calculo el puntaje de visualizaciones
                $visualizaciones = SesionVis::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_ultimo_video', '>=', $corte->fecha_inicio)
                            ->where('fecha_ultimo_video', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                // Calculo el puntaje de Evaluaciones
                $evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                // Calculo el puntaje de Trivias
                $trivia = TriviaRes::where('id_usuario',$id_usuario)
                    ->where('id_temporada',$id_temporada)
                    ->where('fecha_registro', '>=', $corte->fecha_inicio)
                    ->where('fecha_registro', '<=', $corte->fecha_final)
                    ->pluck('puntaje')->sum();
                // Calculo el puntaje de Jackpots
                $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                // Calculo el puntaje de Extras
                $extra = PuntosExtra::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntos')->sum();
                // Hago la suma
                $puntaje_total = $visualizaciones+$evaluaciones+$trivia+$jackpots+$extra;
                // Vacio los datos en el puntaje total y creditos
                $corte_usuario->puntaje = $puntaje_total;
                $corte_usuario->creditos = $puntaje_total;
                $corte_usuario->fecha_corte = date('Y-m-d');
                // Guardo el corte del usuario
                $corte_usuario->save();
                // Vacio los puntos en las variables para enviar
                $creditos_total = $puntaje_total;
                $canjeo_transacciones = CanjeoTransacciones::where('id_usuario',$id_usuario)
                                                            ->where('id_temporada',$id_temporada)
                                                            ->pluck('creditos')->sum();
                if(!$canjeo_transacciones){
                    $creditos_consumidos = 0;
                }else{
                    $creditos_consumidos = $canjeo_transacciones;
                }
                // Vacio los datos del corte del usuario en la variablede datos
                $datos_corte_usuario = $corte_usuario;
            }else{
                // Vacio los datos del corte del usuario en la variable de datos
                $datos_corte_usuario = $corte_usuario;

                // Recalculo puntos

                // Calculo el puntaje de visualizaciones
                $visualizaciones = SesionVis::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_ultimo_video', '>=', $corte->fecha_inicio)
                            ->where('fecha_ultimo_video', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                // Calculo el puntaje de Evaluaciones
                $evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                // Calculo el puntaje de Trivias
                $trivia = TriviaRes::where('id_usuario',$id_usuario)
                    ->where('id_temporada',$id_temporada)
                    ->where('fecha_registro', '>=', $corte->fecha_inicio)
                    ->where('fecha_registro', '<=', $corte->fecha_final)
                    ->pluck('puntaje')->sum();
                // Calculo el puntaje de Jackpots
                $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                // Calculo el puntaje de Extras
                $extra = PuntosExtra::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntos')->sum();
                // Hago la suma
                $puntaje_total = $visualizaciones+$evaluaciones+$trivia+$jackpots+$extra;
                $creditos_total = $puntaje_total;
                // Vacio los datos en el puntaje total y creditos
                $corte_usuario->puntaje = $puntaje_total;
                $corte_usuario->creditos = $puntaje_total;
                $corte_usuario->save();
                
                $canjeo_transacciones = CanjeoTransacciones::where('id_usuario',$id_usuario)
                                                            ->where('id_temporada',$id_temporada)
                                                            ->pluck('creditos')->sum();
                if(!$canjeo_transacciones){
                    $creditos_consumidos = 0;
                }else{
                    $creditos_consumidos = $canjeo_transacciones;
                }
            }
        }
        

        $completo = [
        'productos' => $productos,
        'usuario' => $usuario,
        'corte' => $datos_corte,
        'corte_usuario' => $datos_corte_usuario,
        'puntaje_total' => $puntaje_total,
        'creditos_total' => $creditos_total,
        'creditos_consumidos' => $creditos_consumidos,
        'creditos_restantes' =>$creditos_total-$creditos_consumidos
        ];

        return response()->json($completo);
        
    }

    public function detalles_producto_api(Request $request)
    {
        //
        $producto = CanjeoProductos::find($request->input('id'));
        $galeria = CanjeoProductosGaleria::where('id_producto', $producto->id)->orderBy('orden')->get();
        $canjeados = CanjeoTransaccionesProductos::where('id_producto', $producto->id)->get();
        $completo = [
            'producto' => $producto,
            'galeria' => $galeria,
            'canjeados' => $canjeados,
            ];
    
            return response()->json($completo);
    }

    public function canje_checkout_api(Request $request)
    {
        DB::beginTransaction(); // Iniciar la transacción

        try {
            $id_cuenta = $request->input('idCuenta');
            $cuenta = Cuenta::find($id_cuenta);
            $idUsuario = $request->input('idUsuario');
            $idCorte = $request->input('idCorte');
            $nombreCompleto = $request->input('nombreCompleto');
            $calle = $request->input('calle');
            $numeroExt = $request->input('numeroExt');
            $numeroInt = $request->input('numeroInt');
            $colonia = $request->input('colonia');
            $municipio = $request->input('municipio');
            $ciudad = $request->input('ciudad');
            $codigoPostal = $request->input('codigoPostal');
            $horario = $request->input('horario');
            $telefono = $request->input('telefono');
            $referencia = $request->input('referencia');
            $notas = $request->input('notas');
            $carrito = $request->input('carrito');
            $creditos_finales = 0;

            foreach ($carrito as $producto) {
                $creditos_finales += $producto['creditos_totales'];
            }

            // Guardo la transacción
            $transaccion = new CanjeoTransacciones();
            $transaccion->id_temporada = $cuenta->temporada_actual;
            $transaccion->id_corte = $idCorte;
            $transaccion->id_usuario = $idUsuario;
            $transaccion->creditos = $creditos_finales;
            $transaccion->direccion_nombre = $nombreCompleto;
            $transaccion->direccion_calle = $calle;
            $transaccion->direccion_numero = $numeroExt;
            $transaccion->direccion_numeroint = $numeroInt;
            $transaccion->direccion_colonia = $colonia;
            $transaccion->direccion_municipio = $municipio;
            $transaccion->direccion_ciudad = $ciudad;
            $transaccion->direccion_codigo_postal = $codigoPostal;
            $transaccion->direccion_horario = $horario;
            $transaccion->direccion_telefono = $telefono;
            $transaccion->direccion_referencia = $referencia;
            $transaccion->direccion_notas = $notas;
            $transaccion->confirmado = 'no';
            $transaccion->enviado = 'no';
            $transaccion->fecha_registro = date('Y-m-d');
            $transaccion->save();

            foreach ($carrito as $producto) {
                $producto_transaccion = new CanjeoTransaccionesProductos();
                $producto_transaccion->id_transacciones = $transaccion->id;
                $producto_transaccion->id_temporada = $transaccion->id_temporada;
                $producto_transaccion->id_producto = $producto['id'];
                $producto_transaccion->nombre = $producto['nombre'];
                $producto_transaccion->variacion = $producto['variacion'];
                $producto_transaccion->cantidad = $producto['cantidad'];
                $producto_transaccion->creditos_unitario = $producto['creditos_unidad'];
                $producto_transaccion->creditos_totales = $producto['creditos_totales'];
                $producto_transaccion->save();
            }

            DB::commit(); // Confirmar la transacción
            return response()->json(['success' => true, 'id_transaccion' => $transaccion->id]);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function detalles_transaccion_api(Request $request)
    {
        //
        $transaccion = CanjeoTransacciones::find($request->input('id_transaccion'));
        $usuario = User::find($request->input('id_usuario'));
        $productos = CanjeoTransaccionesProductos::where('id_transacciones', $transaccion->id)->get();
        foreach($productos as $producto){
            $detalles_producto = CanjeoProductos::find($producto->id_producto);
            $producto->imagen = $detalles_producto->imagen;
        }
        $completo = [
            'usuario' => $usuario,
            'transaccion' => $transaccion,
            'productos' => $productos,
            ];
    
            return response()->json($completo);
    }

    public function canje_checkout_actualizar_api(Request $request)
    {
        DB::beginTransaction(); // Iniciar la transacción

        try {
            $id_transaccion = $request->input('idTransaccion');
            $nombreCompleto = $request->input('nombreCompleto');
            $calle = $request->input('calle');
            $numeroExt = $request->input('numeroExt');
            $numeroInt = $request->input('numeroInt');
            $colonia = $request->input('colonia');
            $municipio = $request->input('municipio');
            $ciudad = $request->input('ciudad');
            $codigoPostal = $request->input('codigoPostal');
            $horario = $request->input('horario');
            $telefono = $request->input('telefono');
            $referencia = $request->input('referencia');
            $notas = $request->input('notas');

            // Guardo la transacción
            $transaccion = CanjeoTransacciones::find($id_transaccion);
            
            $transaccion->direccion_nombre = $nombreCompleto;
            $transaccion->direccion_calle = $calle;
            $transaccion->direccion_numero = $numeroExt;
            $transaccion->direccion_numeroint = $numeroInt;
            $transaccion->direccion_colonia = $colonia;
            $transaccion->direccion_municipio = $municipio;
            $transaccion->direccion_ciudad = $ciudad;
            $transaccion->direccion_codigo_postal = $codigoPostal;
            $transaccion->direccion_horario = $horario;
            $transaccion->direccion_telefono = $telefono;
            $transaccion->direccion_referencia = $referencia;
            $transaccion->direccion_notas = $notas;
            $transaccion->save();

            DB::commit(); // Confirmar la transacción
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function checar_mail_canje(Request $request)
    {

        $transaccion_productos = CanjeoTransaccionesProductos::where('id_transacciones', '89')->get();
        $url_admin = '#';
        // Datos a pasar a la vista de email
        $data_admin = [
            'titulo' => 'PRUEBA IGNORAR - Un nuevo canje ha llegado',
            'productos' => $transaccion_productos,
            'boton_texto' => 'Detalle de los productos',
            'boton_enlace' => $url_admin
        ];

        $data = [
            'titulo' => 'PRUEBA IGNORAR -  ¡El premio que seleccionaste ya está en camino!',
            'productos' => $transaccion_productos,

        ];

        // Enviar el correo

        Mail::to('pl-electrico@panduitlatam.com')->send(new ConfirmacionCanje($data_admin));
        //Mail::to('marmocreativo@gmail.com')->send(new ConfirmacionCanje($data_admin));
        Mail::to('marmocreativo@gmail.com')->send(new ConfirmacionCanjeUsuario($data));
    }

    public function canje_checkout_confirmar_api(Request $request)
{
    DB::beginTransaction(); // Iniciar la transacción

    try {
        $id_transaccion = $request->input('idTransaccion');

        // Guardo la transacción
        $transaccion = CanjeoTransacciones::find($id_transaccion);
        $transaccion_productos = CanjeoTransaccionesProductos::where('id_transacciones', $id_transaccion)->get();
        $usuario = User::find($transaccion->id_usuario);
        
        $transaccion->confirmado = 'si';
        $transaccion->fecha_confirmado = date('Y-m-d');
        $transaccion->save();
        $url_admin = 'https://plsystem.quarkservers2.com/admin/canjeo/transacciones_usuario?id_temporada='.$transaccion->id_temporada.'&id_corte='.$transaccion->id_corte.'&id_usuario='.$transaccion->id_usuario;

        // Envio el mail

        // Generar la tabla HTML con las variables correctamente
        

                        // Datos a pasar a la vista de email
                        $data_admin = [
                            'titulo' => 'Un nuevo canje ha llegado',
                            'productos' => $transaccion_productos,
                            'boton_texto' => 'Detalle de los productos',
                            'boton_enlace' => $url_admin
                        ];

                        $data = [
                            'titulo' => '¡El premio que seleccionaste ya está en camino!',
                            'productos' => $transaccion_productos,

                        ];

                        // Enviar el correo

                        Mail::to('pl-electrico@panduitlatam.com')->send(new ConfirmacionCanje($data_admin));
                        //Mail::to('marmocreativo@gmail.com')->send(new ConfirmacionCanje($data_admin));
                        Mail::to($usuario->email)->send(new ConfirmacionCanjeUsuario($data));

                        DB::commit(); // Confirmar la transacción
                        return response()->json(['success' => true]);

                    } catch (\Exception $e) {
                        DB::rollBack(); // Revertir la transacción en caso de error
                        return response()->json(['success' => false, 'error' => $e->getMessage()]);
                    }
                }

    public function lista_transacciones_api(Request $request)
    {
        //
        $id_cuenta = $request->input('id_cuenta');
        $cuenta = Cuenta::find($id_cuenta);
        $id_temporada = $cuenta->temporada_actual;
        $temporada = Temporada::find($id_temporada);
        $id_usuario = $request->input('id_usuario');
        $usuario = User::find($id_usuario);
        $transacciones = CanjeoTransacciones::where('id_usuario', $id_usuario)->where('id_temporada', $id_temporada)->get();
        foreach($transacciones as $transaccion){
            $productos = CanjeoTransaccionesProductos::where('id_transacciones', $transaccion->id)->get();
            foreach($productos as $producto){
                $detalles_producto = CanjeoProductos::find($producto->id_producto);
                $producto->imagen = $detalles_producto->imagen;
            }

            $transaccion->productos = $productos;
        }
        $completo = [
            'usuario' => $usuario,
            'transacciones' => $transacciones,
            ];
    
            return response()->json($completo);
    }

}
