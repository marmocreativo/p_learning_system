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
use App\Models\Publicacion;
use App\Models\AccionesUsuarios;
use App\Models\Tokens;
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

class NoticiasExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function collection()
    {
        $id_temporada = $this->request->input('id_temporada');

        // Obtener las publicaciones tipo "noticia" de la temporada
        $publicaciones = Publicacion::where('id_temporada', $id_temporada)
                                    ->where('clase', 'noticia')
                                    ->get();

        $reporte = [];

        // Calcular totales
        $totalNoticias = $publicaciones->count();
        $totalClicks = 0;
        $usuariosUnicos = collect();

        foreach ($publicaciones as $pub) {
            // Contar las acciones de usuarios relacionadas con esta publicación
            $clicks = AccionesUsuarios::where('accion', 'click en noticia')
                ->where('descripcion', 'like', '%Se dio click en la noticia id: '.$pub->id.'%')
                ->count();

            // Obtener usuarios únicos que hicieron click en esta noticia
            $usuariosNoticia = AccionesUsuarios::where('accion', 'click en noticia')
                ->where('descripcion', 'like', '%Se dio click en la noticia id: '.$pub->id.'%')
                ->pluck('id_usuario')
                ->unique();

            // Contar clicks de usuarios únicos para esta noticia
            $clicksUsuariosUnicos = $usuariosNoticia->count();

            // Agregar al total de clicks
            $totalClicks += $clicks;

            // Agregar usuarios únicos al conjunto total (sin duplicados)
            $usuariosUnicos = $usuariosUnicos->merge($usuariosNoticia)->unique();

            $reporte[] = [
                'Publicacion' => $pub->titulo ?? 'Publicación '.$pub->id,
                'Clicks' => $clicks,
                'Clicks Usuarios Únicos' => $clicksUsuariosUnicos,
            ];
        }

        // Agregar fila de totales al inicio
        $reporte = array_merge([
            [
                'Publicacion' => 'TOTALES',
                'Clicks' => $totalClicks,
                'Clicks Usuarios Únicos' => $usuariosUnicos->count(),
            ],
            [
                'Publicacion' => 'Total Noticias: ' . $totalNoticias,
                'Clicks' => '',
                'Clicks Usuarios Únicos' => '',
            ],
            [
                'Publicacion' => '--- DETALLE POR NOTICIA ---',
                'Clicks' => '',
                'Clicks Usuarios Únicos' => '',
            ]
        ], $reporte);

        return collect($reporte);
    }
    
    public function headings(): array
    {
        return [
            'Publicacion',
            'Clicks',
            'Clicks Usuarios Únicos',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Aplicar formato a los encabezados
                $event->sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '213746']
                    ],
                ]);

                // Aplicar formato especial a las filas de totales (filas 2, 3 y 4)
                $event->sheet->getStyle('A2:C4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'E6E6E6']
                    ],
                ]);
            },
        ];
    }
}