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

use App\Exports\ReporteTemporadaExport;
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
        $id_temporada = $producto->id_temporada;
        $temporada = Temporada::find($id_temporada);
        $galeria = CanjeoProductosGaleria::where('id_producto', $id)->orderBy('orden')->get();
        return view('admin/canjeo_productos_editar', compact('producto', 'temporada', 'galeria'));
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
        $usuarios = User::all();
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

        // consulta
        $productos = CanjeoProductos::where('id_temporada', $id_temporada)->get();
        $corte = CanjeoCortes::where('id_temporada', $id_temporada)
                    ->where('fecha_publicacion_inicio', '<=', $fecha_actual)
                    ->where('fecha_publicacion_final', '>', $fecha_actual)
                    ->first();

        if(!$corte){
            $corte_anterior = CanjeoCortes::where('id_temporada', $id_temporada)
                    ->where('fecha_inicio', '<=', $fecha_actual)
                    ->where('fecha_final', '>=', $fecha_actual)
                    ->first();
            $datos_corte = null;
            $datos_corte_usuario = null;
            $visualizaciones = SesionVis::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_ultimo_video', '>=', $corte_anterior->fecha_inicio)
                            ->where('fecha_ultimo_video', '<=', $corte_anterior->fecha_final)
                            ->pluck('puntaje')->sum();
                $evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte_anterior->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte_anterior->fecha_final)
                            ->pluck('puntaje')->sum();
                $trivia = TriviaRes::where('id_usuario',$id_usuario)
                    ->where('id_temporada',$id_temporada)
                    ->where('fecha_registro', '>=', $corte_anterior->fecha_inicio)
                    ->where('fecha_registro', '<=', $corte_anterior->fecha_final)
                    ->pluck('puntaje')->sum();
                $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte_anterior->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte_anterior->fecha_final)
                            ->pluck('puntaje')->sum();
                $extra = 0;
                $puntaje_total = $visualizaciones+$evaluaciones+$trivia+$jackpots+$extra;
            $creditos_total = 0;
            $creditos_consumidos = 0;
        }else{
            $datos_corte=$corte;
            $corte_usuario = CanjeoCortesUsuarios::where('id_corte', $corte->id)
            ->where('id_usuario', $id_usuario)
            ->first();
            if(!$corte_usuario){
                $corte_usuario = new CanjeoCortesUsuarios();
                $corte_usuario->id_corte = $corte->id;
                $corte_usuario->id_temporada = $id_temporada;
                $corte_usuario->id_usuario = $id_usuario;
                // Calculo el puntaje
                $visualizaciones = SesionVis::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_ultimo_video', '>=', $corte->fecha_inicio)
                            ->where('fecha_ultimo_video', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                $evaluaciones = EvaluacionRes::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                $trivia = TriviaRes::where('id_usuario',$id_usuario)
                    ->where('id_temporada',$id_temporada)
                    ->where('fecha_registro', '>=', $corte->fecha_inicio)
                    ->where('fecha_registro', '<=', $corte->fecha_final)
                    ->pluck('puntaje')->sum();
                $jackpots = JackpotIntentos::where('id_usuario',$id_usuario)
                            ->where('id_temporada',$id_temporada)
                            ->where('fecha_registro', '>=', $corte->fecha_inicio)
                            ->where('fecha_registro', '<=', $corte->fecha_final)
                            ->pluck('puntaje')->sum();
                $extra = 0;
                $puntaje_total = $visualizaciones+$evaluaciones+$trivia+$jackpots+$extra;
                $corte_usuario->puntaje = $puntaje_total;
                $corte_usuario->creditos = $puntaje_total;
                $corte_usuario->fecha_corte = date('Y-m-d');
                $corte_usuario->save();
                $creditos_total = $puntaje_total;
                $creditos_consumidos = 0;
                $datos_corte_usuario = $corte_usuario;
            }else{
                $datos_corte_usuario = $corte_usuario;
                $puntaje_total = $corte_usuario->puntaje;
                $creditos_total = $corte_usuario->creditos;
                $canjeo_transacciones = CanjeoTransacciones::where('id_usuario',$id_usuario)
                                                            ->where('id_corte',$corte->id)
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
        $completo = [
            'producto' => $producto,
            'galeria' => $galeria,
            ];
    
            return response()->json($completo);
    }

}
