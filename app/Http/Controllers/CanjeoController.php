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
use App\Models\CanjeoCortes;
use App\Models\CanjeoCortesUsuarios;
use App\Models\CanjeoProductos;
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
    }

    public function productos_guardar(Request $request)
    {
        //
    }
    public function productos_actualizar(Request $request, string $id)
    {
        //
    }
    public function productos_borrar(string $id)
    {
        //
    }

    // Cortes
    public function cortes(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $cortes = CanjeoCortes::where('id_temporada', $id_temporada)->get();
        return view('admin/canjeo_cortes', compact('cortes', 'temporada'));
    }

    public function cortes_guardar(Request $request)
    {
        //
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

    

}
