<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function upload(Request $request)
    {
        // Valida que se haya enviado un archivo
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        // Guarda el archivo Excel en el servidor
        $file = $request->file('excel_file');
        $filePath = $file->storeAs('uploads', $file->getClientOriginalName());

        // Lee el archivo Excel y obtén sus datos
        //$data = Excel::toArray([], $filePath);

        // Procesa los datos como desees
        // $data contendrá los datos del archivo Excel
        dd($data);
    }
}