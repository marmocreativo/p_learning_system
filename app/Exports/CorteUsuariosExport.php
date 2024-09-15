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
        
        $coleccion = null;
        
        $cortes_usuarios = CanjeoCortesUsuarios::where('id_corte', $this->request->input('id_corte'))->get();
        $transacciones = CanjeoTransacciones::where('id_corte', $this->request->input('id_corte'))->get();
        foreach($cortes_usuarios as $corte){
            $numero_transacciones = 0;
            $usuario = User::find($corte->id_usuario);
            foreach($transacciones as $transaccion){
                if($transaccion->id_corte == $corte->id_corte && $transaccion->id_usuario == $corte->id_usuario){
                    $numero_transacciones++;
                }
            }
            $coleccion[] = [
                $usuario->nombre.' '.$usuario->apellidos,
                $usuario->email,
                (string) $corte->creditos,
                (string) $numero_transacciones,
                $corte->fecha_corte
            ];
        }

        return collect($coleccion);
            
    }
    public function headings(): array
    {
        return [
            'Nombre',
            'Email',
            'Creditos',
            'Pedidos',
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