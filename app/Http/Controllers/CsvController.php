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
use App\Models\SesionVis;
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

        $temporada = Temporada::find($request->input('id_temporada'));

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

        echo '<table>';
        foreach ($registros as $registro) {
            echo '<tr>';
            $distribuidor = Distribuidor::where('nombre', $registro['DISTY'])->first();
        

                if (!$distribuidor) {
                    $distribuidor = new Distribuidor();
                    
                    $distribuidor->nombre = $registro['DISTY'];
                    $distribuidor->pais = $registro['REGIÓN'];
                    //$distribuidor->region = 'RoLA';
                    $distribuidor->region = 'México';
                    $distribuidor->nivel = $registro['NIVEL DISTY'];
                    $distribuidor->estado = 'activo';

                    $distribuidor->save();
                    echo'<td>Nuevo Distribuidor</td><td>'.$distribuidor->nombre.'</td><td>'.$distribuidor->region.'</td>';
                }else{
                    echo'<td>Distribuidor Existente</td><td>'.$distribuidor->nombre.'</td><td>'.$distribuidor->region.'</td>';
                }

            $usuario = User::where('email', $registro['MAIL'])->first();
        

            if (!$usuario) {
                $usuario = new User();
                
                $usuario->legacy_id = uniqid('',true);
                $usuario->nombre = $registro['NOMBRE(s)'];
                $usuario->apellidos = $registro['APELLIDO(s)'];
                $usuario->email = $registro['MAIL'];
                $usuario->telefono = '';
                //$usuario->whatsapp = $registro['WHATSAPP LIDER'];
                $usuario->whatsapp = '';
                $usuario->fecha_nacimiento = null;
                $usuario->password = Hash::make($registro['PASS']);
                $usuario->lista_correo = 'si';
                $usuario->imagen = 'default.jpg';
                $usuario->clase = 'usuario';
                $usuario->estado = 'activo';
    
                $usuario->save();
                echo'<td>Nuevo usuario</td><td>'.$usuario->nombre.' '.$usuario->apellidos.'</td><td>'.$usuario->email.'</td>';
            }else{
                echo'<td>Usuario existente</td><td>'.$usuario->nombre.' '.$usuario->apellidos.'</td><td>'.$usuario->email.'</td>';
            }

            


                $suscripcion_dist = DistribuidoresSuscripciones::where('id_distribuidor', $distribuidor->id)->where('id_temporada', 1)->first();
                if (!$suscripcion_dist) {
                    $suscripcion_dist = new DistribuidoresSuscripciones();
                    $suscripcion_dist->id_distribuidor = $distribuidor->id;
                    $suscripcion_dist->id_cuenta = $temporada->id_cuenta;
                    $suscripcion_dist->id_temporada = $temporada->id;
                    $suscripcion_dist->cantidad_usuarios = 0;
                    $suscripcion_dist->nivel = $registro['NIVEL DISTY'];
                    $suscripcion_dist->save();
                    echo'<td>Nueva Suscripción Dist</td><td>'.$suscripcion_dist->id_distribuidor.'</td><td>'.$suscripcion_dist->nivel.'</td>';
                }else{
                    echo'<td>Suscripcion Existente Dist</td><td>'.$suscripcion_dist->id_distribuidor.'</td><td>'.$suscripcion_dist->nivel.'</td>';
                }
                
    
    
            $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 9)->first();
            if (!$suscripcion) {
                $suscripcion = new UsuariosSuscripciones();
                $suscripcion->id_usuario = $usuario->id;
                $suscripcion->id_cuenta = $temporada->id_cuenta;
                $suscripcion->id_temporada = $temporada->id;
                $suscripcion->id_distribuidor = $distribuidor->id;
                $suscripcion->confirmacion_puntos = 'pendiente';
                $suscripcion->nivel_usuario = $registro['VENTAS/ESPECIALISTA'];
                /*
                if($registro['LÍDER']=='no'){
                    $suscripcion->funcion = 'usuario';
                }else{
                    $suscripcion->funcion = 'lider';
                }
                */
                $suscripcion->funcion = 'usuario';
                
                $suscripcion->save();
                echo'<td>Nueva Suscripción Usuario</td><td>'.$suscripcion->id_usuario.'</td><td>'.$suscripcion_dist->funcion.'</td>';
            }else{
                echo'<td>Suscripcion Existente Usuario</td><td>'.$suscripcion->id_usuario.'</td><td>'.$suscripcion_dist->funcion.'</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        
    }

    public function registros_pasados(Request $request)
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
            $distribuidor = Distribuidor::where('nombre', $registro['DISTRIBUIDOR'])->first();
        

                if (!$distribuidor) {
                    $distribuidor = new Distribuidor();
                    
                    $distribuidor->nombre = $registro['DISTRIBUIDOR'];
                    $distribuidor->pais = 'México';
                    //$distribuidor->region = $registro['REGIÓN'];
                    $distribuidor->region = 'México';
                    $distribuidor->nivel = 'completo';
                    $distribuidor->default_pass = '123456';
                    $distribuidor->estado = 'activo';

                    $distribuidor->save();
                }

            $usuario = User::where('email', $registro['CORREO'])->first();
        

            if (!$usuario) {
                $usuario = new User();
                
                $usuario->legacy_id = uniqid('',true);
                $usuario->nombre = $registro['NOMBRE'];
                $usuario->apellidos = $registro['APELLIDOS'];
                $usuario->email = $registro['CORREO'];
                $usuario->telefono = '';
                $usuario->whatsapp = '';
                $usuario->fecha_nacimiento = null;
                $usuario->password = Hash::make($distribuidor->default_pass);
                $usuario->lista_correo = 'si';
                $usuario->imagen = 'default.jpg';
                $usuario->clase = 'usuario';
                $usuario->estado = 'activo';
                $usuario->save();
            }

            


            $suscripcion_dist = DistribuidoresSuscripciones::where('id_distribuidor', $distribuidor->id)->where('id_temporada', 6)->first();
            if (!$suscripcion_dist) {
                $suscripcion_dist = new DistribuidoresSuscripciones();
                $suscripcion_dist->id_distribuidor = $distribuidor->id;
                $suscripcion_dist->id_cuenta = 1;
                $suscripcion_dist->id_temporada = 6;
                $suscripcion_dist->cantidad_usuarios = 0;
                $suscripcion_dist->nivel = 'completo';
                $suscripcion_dist->save();
            }
                

            $visualizacion = SesionVis::where('id_usuario', $usuario->id)->where('id_sesion', $registro['ID SESION'])->where('id_sesion', $registro['ID SESION'])->first();
            if (!$visualizacion) {
                $visualizacion = new SesionVis();
                $visualizacion->id_sesion = $registro['ID SESION'];
                $visualizacion->id_temporada = 6;
                $visualizacion->id_distribuidor = $distribuidor->id;
                $visualizacion->id_usuario = $usuario->id;
                $visualizacion->fecha_ultimo_video = $registro['FECHA'];
                $visualizacion->puntaje = $registro['PUNTAJE'];
                $visualizacion->save();
            }

            $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 6)->first();
            if (!$suscripcion) {
                $suscripcion = new UsuariosSuscripciones();
                $suscripcion->id_usuario = $usuario->id;
                $suscripcion->id_cuenta = 1;
                $suscripcion->id_temporada = 6;
                $suscripcion->id_distribuidor = $distribuidor->id;
                $suscripcion->confirmacion_puntos = 'pendiente';
                $suscripcion->funcion = 'usuario';
                $suscripcion->champions_a = 'si';
                $suscripcion->save();
            }

            $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', 1)->first();
            if (!$suscripcion) {
                $suscripcion = new UsuariosSuscripciones();
                $suscripcion->id_usuario = $usuario->id;
                $suscripcion->id_cuenta = 1;
                $suscripcion->id_temporada = 6;
                $suscripcion->id_distribuidor = $distribuidor->id;
                $suscripcion->confirmacion_puntos = 'pendiente';
                $suscripcion->funcion = 'usuario';
                $suscripcion->champions_a = 'si';
                $suscripcion->save();
            }else{
                $suscripcion->champions_a = 'si';
                $suscripcion->save();
            }

        }
        
    }

    public function actualizar_pass(Request $request)
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
            $distribuidor = Distribuidor::where('nombre', $registro['DISTY'])->first();

            $usuario = User::where('email', $registro['MAIL'])->first();
            if($distribuidor&&$usuario){
                $usuario->password = Hash::make($distribuidor->default_pass);
                $usuario->save();
                echo 'correo: '.$registro['MAIL'].' contraseña: '.$distribuidor->default_pass.'<br>';
            }
            
        }
        
    }


}