<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SkusImport implements ToCollection, WithHeadingRow
{
    public $rows = [];

    public function collection(Collection $rows)
    {
        // Convertir las filas a array para fácil acceso
        foreach ($rows as $row) {
            $this->rows[] = [
                'nombre_del_desafio' => $row['nombre_del_desafio'] ?? $row['desafio'] ?? null,
                'sku' => $row['sku'] ?? null,
                'descripcion' => $row['descripcion'] ?? null,
            ];
        }
    }

    /**
     * Configurar el comportamiento de las cabeceras
     */
    public function headingRow(): int
    {
        return 1; // La primera fila contiene los encabezados
    }
}