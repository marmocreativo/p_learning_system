<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;
use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Distribuidor;
use App\Models\DistribuidoresSuscripciones;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CsvController extends Controller
{
    public function subirCSV(Request $request)
    {
        // Validar el formulario para asegurar que se haya enviado un archivo CSV
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        // Obtener el archivo CSV
        $archivoCSV = $request->file('csv_file');

        // Procesar el archivo CSV
        $csv = Reader::createFromPath($archivoCSV->getPathname(), 'r');
        $csv->setHeaderOffset(0); // Especifica que la primera fila contiene encabezados
        $encabezados = $csv->getHeader(); // Obtiene los encabezados
        $registros = $csv->getRecords(); // Obtiene los registros

        // Puedes hacer algo con los registros, como guardarlos en la base de datos
        // Por ejemplo:
        // foreach ($registros as $registro) {
        //     TuModelo::create($registro);
        // }
        foreach ($registros as $registro) {
            $usuario = User::where('email', $registro['MAIL'])->first();
        

            if (!$usuario) {
                $usuario = new User();
                
                $usuario->legacy_id = uniqid('',true);
                $usuario->nombre = $registro['NOMBRE(s)'];
                $usuario->apellidos = $registro['APELLIDO(s)'];
                $usuario->email = $registro['MAIL'];
                $usuario->telefono = '';
                $usuario->whatsapp = $registro['WHATSAPP LIDER'];
                $usuario->fecha_nacimiento = null;
                $usuario->password = Hash::make($registro['PASS']);
                $usuario->lista_correo = 'si';
                $usuario->imagen = 'default.jpg';
                $usuario->clase = 'usuario';
                $usuario->estado = 'activo';
    
                $usuario->save();
            }

            $distribuidor = Distribuidor::where('nombre', $registro['DISTY'])->first();
        

                if (!$distribuidor) {
                    $distribuidor = new Distribuidor();
                    
                    $distribuidor->nombre = $request->Nombre;
                    $distribuidor->pais = $request->Pais;
                    $distribuidor->region = $request->Region;
                    $distribuidor->nivel = $request->Nivel;
                    $distribuidor->estado = $request->Estado;

                    $distribuidor->save();
                }


                $suscripcion_dist = DistribuidoresSuscripciones::where('id_distribuidor', $distribuidor->id)->where('id_temporada', 1)->first();
                if (!$suscripcion_dist) {
                    $suscripcion_dist = new DistribuidoresSuscripciones();
                    $suscripcion_dist->id_distribuidor = $distribuidor->id;
                    $suscripcion_dist->id_cuenta = 1;
                    $suscripcion_dist->id_temporada = 1;
                    $suscripcion_dist->cantidad_usuarios = 0;
                    $suscripcion_dist->nivel = 'completo';
                    $suscripcion_dist->save();
                }
                
    
    
            $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 1)->first();
            if (!$suscripcion) {
                $suscripcion = new UsuariosSuscripciones();
                $suscripcion->id_usuario = $usuario->id;
                $suscripcion->id_cuenta = 1;
                $suscripcion->id_temporada = 1;
                $suscripcion->id_distribuidor = $distribuidor->id;
                $suscripcion->confirmacion_puntos = 'pendiente';
                if($registro['LÍDER']=='no'){
                    $suscripcion->funcion = 'usuario';
                }else{
                    $suscripcion->funcion = 'lider';
                }
                $suscripcion->save();
            }
        }
        
    }
}