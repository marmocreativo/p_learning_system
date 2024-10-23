<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Distribuidor;
use App\Models\DistribuidorSuscripciones;
use App\Models\SesionVis;
use App\Models\SesionEv;
use App\Models\EvaluacionRes;
use App\Models\TriviaGanador;
use App\Models\TriviaRes;
use App\Models\Trivia;
use App\Models\JackpotIntentos;
use App\Models\JackpotRes;
use App\Models\Jackpot;
use App\Models\Cuenta;
use App\Models\Tokens;
use App\Models\CanjeoCortes;
use App\Models\CanjeoCortesUsuarios;
use App\Models\CanjeoProductos;
use App\Models\CanjeoProductosGaleria;
use App\Models\CanjeoTransacciones;
use App\Models\CanjeoTransaccionesProductos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

class CorteUsuariosExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function collection()
{
    $coleccion = [];

    $cortes_usuarios = CanjeoCortesUsuarios::where('id_corte', $this->request->input('id_corte'))->get();
    $transacciones = CanjeoTransacciones::where('id_corte', $this->request->input('id_corte'))->get();
    
    foreach($transacciones as $transaccion){
        $usuario = User::find($transaccion->id_usuario);
        $suscripcion = UsuariosSuscripciones::where('id_temporada', $transaccion->id_temporada)
                                            ->where('id_usuario', $transaccion->id_usuario)->first();
        if($suscripcion){
            $distribuidor = Distribuidor::find($suscripcion->id_distribuidor);
            $distribuidor_nombre = $distribuidor->nombre;
            $region = $distribuidor->region;
        }else{
            $distribuidor_nombre = 'N/A';
            $region = 'N/A';
        }

        $productos = CanjeoTransaccionesProductos::where('id_transacciones', $transaccion->id)->get();

        // Inicializa los arrays para productos, variaciones y cantidades
        $producto = ['','',''];
        $variacion = ['','',''];
        $cantidad = ['','',''];
        
        // Cambia el nombre de la variable dentro del foreach para evitar el conflicto
        $index = 0;
        foreach($productos as $prod){  // Cambié $producto por $prod aquí
            if ($index < 3) {  // Para asegurarte de que no exceda el tamaño del array predefinido
                $producto[$index] = $prod->nombre;
                $variacion[$index] = $prod->variacion;
                $cantidad[$index] = $prod->cantidad;
                $index++;
            }
        }

        $direccion = $transaccion->direccion_calle
                    .' No.'.$transaccion->direccion_numero
                    .' No. Int.'.$transaccion->direccion_numeroint
                    .' Col.'.$transaccion->direccion_colonia
                    .' Munic.'.$transaccion->direccion_municipio
                    .' Ciud.'.$transaccion->direccion_ciudad
                    .' CP.'.$transaccion->direccion_codigo_postal;
                    
        $coleccion[] = [
            $transaccion->id,
            $usuario->nombre.' '.$usuario->apellidos,
            $usuario->email,
            $distribuidor_nombre,
            $region,
            $producto[0], // Primer producto
            $variacion[0],
            $cantidad[0],
            $producto[1], // Segundo producto (si existe)
            $variacion[1],
            $cantidad[1],
            $producto[2], // Tercer producto (si existe)
            $variacion[2],
            $cantidad[2],
            $transaccion->direccion_nombre,
            $transaccion->direccion_telefono,
            $direccion,
            $transaccion->direccion_referencias,
            $transaccion->direccion_horario,
            $transaccion->direccion_notas,
            $transaccion->fecha_registro
        ];
    }

    return collect($coleccion);
}

    public function headings(): array
    {
        return [
            'Folio',
            'Nombre',
            'Email',
            'Distribuidor',
            'Region',
            'Producto 1',
            'Variacion 1',
            'Cantidad 1',
            'Producto 2',
            'Variacion 2',
            'Cantidad 2',
            'Producto 3',
            'Variacion 3',
            'Cantidad 3',
            'Recibe',
            'Telefono',
            'Dirección',
            'Referencias',
            'Horarios',
            'Notas',
            'Fecha'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Aplicar formato a los encabezados
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '213746']
                    ],
                ]);
            },
        ];
    }
}