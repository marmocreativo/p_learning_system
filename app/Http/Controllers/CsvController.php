<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;
use App\Models\User;
use App\Models\UsuariosSuscripciones;
use App\Models\Temporada;
use App\Models\Clase;
use App\Models\Cuenta;
use App\Models\Distribuidor;
use App\Models\DistribuidoresSuscripciones;
use App\Models\SesionVis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\UsersImport;

class CsvController extends Controller
{
    public function importar_usuarios(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $import = new UsersImport;
        Excel::import($import, $request->file('file'));

        $rows = $import->rows;
        $emails = $rows->pluck('correo'); // Cambia 'correo' por el nombre real de la columna en tu Excel
        $temporada = Temporada::where('id', $request->input('id_temporada'))->first();
        $cuenta = Cuenta::where('id', $temporada->id_cuenta)->first();
        $users = User::whereIn('email', $emails)->get();

        switch ($request->input('accion')) {
            case 'agregar':
                //Hago el bucle por cada usuario 
                $rowData = $rows->map(function($row) use ($users, $temporada, $cuenta) {
                    $fila = array();
                    $user = $users->firstWhere('email', $row['correo']);
                    $fila['correo'] = $row['correo'];
                    if(!$user){
                        $user = new User;

                        list($nombreUsuario, $dominio) = explode('@', $row['correo']);

                        // Generar 3 números aleatorios
                        $numerosAleatorios = rand(100, 999);

                        // Concatenar los números aleatorios al nombre de usuario
                        $nuevoNombreUsuario = $nombreUsuario . $numerosAleatorios;
                        
                        $user->nombre = $row['nombre'];
                        $user->apellidos = $row['apellidos'];
                        $user->email = $row['correo'];
                        $user->legacy_id = $nuevoNombreUsuario;
                        $user->telefono = '';
                        $user->whatsapp = '';
                        $user->fecha_nacimiento = null;
                        $user->password = Hash::make($row['password']);
                        $user->lista_correo = 'si';
                        $user->imagen = 'default.jpg';
                        $user->clase = 'usuario';
                        $user->estado = 'activo';
                        $user->save();
                        $fila['usuario_registrado'] = true;
                    }else{
                        $fila['usuario_registrado'] = false;
                    }

                    $distribuidor = Distribuidor::where('nombre', $row['disty'])->first();
                    $fila['disty'] = $row['disty'];

                    if (!$distribuidor) {
                        $distribuidor = new Distribuidor();
                        
                        $distribuidor->nombre = $row['disty'];
                        $distribuidor->pais = $row['region'];
                        $distribuidor->region = $row['region'];
                        $distribuidor->nivel = $row['nivel_disty'];
                        $distribuidor->estado = 'activo';
    
                        $distribuidor->save();
                        $fila['disty_registrado'] = true;
                    }else{
                        $fila['disty_registrado'] = false;
                    }

                    $suscripcion = UsuariosSuscripciones::where('id_usuario', $user->id)->where('id_temporada', $temporada->id)->first();
                    if (!$suscripcion) {
                        $suscripcion = new UsuariosSuscripciones();
                        $suscripcion->id_usuario = $user->id;
                        $suscripcion->id_cuenta = $temporada->id_cuenta;
                        $suscripcion->id_temporada = $temporada->id;
                        $suscripcion->id_distribuidor = $distribuidor->id;
                        $suscripcion->confirmacion_puntos = 'pendiente';
                        $suscripcion->nivel_usuario = $row['nivel_usuario'];
                        $suscripcion->funcion = $row['lider'];
                        
                        
                        $suscripcion->save();
                        $fila['suscripcion_registrada'] = true;
                    }else{
                        $fila['suscripcion_registrada'] = false;
                    }

                    return $fila;
                });

                return view('admin.usuario_importar_agregados', [
                    'rows' => $rowData
                ]);
                break;
            
            case 'actualizar':
                //Hago el bucle por cada usuario 
                $rowData = $rows->map(function($row) use ($users, $temporada, $cuenta) {
                    $fila = array();
                    $user = $users->firstWhere('email', $row['correo']);
                    $fila['correo'] = $row['correo'];
                    if($user){
                        $user->nombre = $row['nombre'];
                        $user->apellidos = $row['apellidos'];
                        $user->save();
                        
                        
                        $fila['usuario_actualizado'] = true;
                        $distribuidor = Distribuidor::where('nombre', $row['disty'])->first();

                        if ($distribuidor) {

                            $distribuidor->nombre = $row['disty'];
                            $distribuidor->region = $row['region'];
                            $distribuidor->nivel = $row['nivel_disty'];
        
                            $distribuidor->save();
                            $fila['disty_actualizado'] = true;
                        }else{
                            $fila['disty_actualizado'] = false;
                        }

                        $suscripcion = UsuariosSuscripciones::where('id_usuario', $user->id)->where('id_temporada', $temporada->id)->first();
                        if ($suscripcion) {
                            $suscripcion->id_usuario = $user->id;
                            $suscripcion->id_cuenta = $temporada->id_cuenta;
                            $suscripcion->id_temporada = $temporada->id;
                            $suscripcion->id_distribuidor = $distribuidor->id;
                            $suscripcion->confirmacion_puntos = 'pendiente';
                            $suscripcion->nivel_usuario = $row['nivel_usuario'];
                            $suscripcion->nivel = $row['nivel_disty'];
                            $suscripcion->funcion = $row['lider'];
                            
                            
                            $suscripcion->save();
                            $fila['suscripcion_actualizada'] = true;
                        }else{
                            $fila['suscripcion_actualizada'] = false;
                        }

                    }else{
                        $fila['usuario_actualizado'] = false;
                        $fila['disty_actualizado'] = false;
                        $fila['suscripcion_actualizada'] = false;
                    }

                    

                    

                    return $fila;
                });

                return view('admin.usuario_importar_actualizados', [
                    'rows' => $rowData
                ]);
                break;
                
            default:
                    $rowData = $rows->map(function($row) use ($users, $temporada, $cuenta) {
                        $user = $users->firstWhere('email', $row['correo']);
                        
                        //dd($distribuidor);
                        if(!empty($user)){
                            $suscripcion = UsuariosSuscripciones::where('id_usuario', $user->id)->where('id_temporada', $temporada->id)->first();
                            $distribuidor = $suscripcion ? Distribuidor::where('id', $suscripcion->id_distribuidor)->first() : null;

                            $fila = [
                                'nombre' => $row['nombre'], 
                                'nombre_registrado' => $user->nombre, 
                                'apellidos' => $row['apellidos'],
                                'apellidos_registrado' => $user->apellidos,
                                'correo' => $row['correo'],
                                'correo_registrado' => $user->email,
                                'nivel_usuario' => $row['nivel_usuario'],
                                'lider' => $row['lider'],
                                'disty' => $row['disty'],
                                'nivel_disty' => $row['nivel_disty'],
                                'usuario' => $row['usuario'],
                                'usuario_registrado' => $user->legacy_id, 
                                'region' => $row['region'],
                                'registrado' => $user ? true : false,
                                'nombre_coincide' => $user && $user->nombre == $row['nombre'] ? true : false,
                                'apellidos_coincide' => $user && $user->apellidos == $row['apellidos'] ? true : false,
                                'usuario_coincide' => $user && $user->legacy_id == $row['usuario'] ? true : false,
                            ];

                        }else{
                            $suscripcion = null;
                            $distribuidor = null;

                            $fila = [
                                'nombre' => $row['nombre'], 
                                'nombre_registrado' => '-', 
                                'apellidos' => $row['apellidos'],
                                'apellidos_registrado' => '-',
                                'correo' => $row['correo'],
                                'correo_registrado' => '-',
                                'nivel_usuario' => $row['nivel_usuario'],
                                'lider' => $row['lider'],
                                'disty' => $row['disty'],
                                'nivel_disty' => $row['nivel_disty'],
                                'usuario' => $row['usuario'],
                                'usuario_registrado' => '-', 
                                'region' => $row['region'],
                                'registrado' => false,
                                'nombre_coincide' => false,
                                'apellidos_coincide' => false,
                                'usuario_coincide' => false,
                            ];
                        }
                        
            
                        if(!empty($suscripcion)){
                            $fila['nivel_usuario_registrado'] = $suscripcion->nivel_usuario;
                            $fila['nivel_coincide'] = $suscripcion && $suscripcion->nivel_usuario == $row['nivel_usuario'] ? true : false;
                            $fila['lider_registrado'] = $suscripcion->funcion;
                            $fila['lider_coincide'] = $suscripcion && $suscripcion->funcion == $row['lider'] ? true : false;
                        }else{
                            $fila['nivel_usuario_registrado'] = '';
                            $fila['nivel_coincide'] = false;
                            $fila['lider_registrado'] = '';
                            $fila['lider_coincide'] = false;
                        }
            
                        if(!empty($distribuidor)){
                            $fila['disty_registrado'] = $distribuidor->nombre;
                            $fila['disty_coincide'] = $distribuidor && $distribuidor->nombre == $row['disty'] ? true : false;
                            $fila['nivel_disty_registrado'] = $distribuidor->nivel;
                            $fila['nivel_disty_coincide'] = $distribuidor && $distribuidor->nivel == $row['nivel_disty'] ? true : false;
                            $fila['region_registrado'] = $distribuidor->region;
                            $fila['region_coincide'] = $distribuidor && $distribuidor->region == $row['region'] ? true : false;
                        }else{
                            $fila['disty_registrado'] = '';
                            $fila['disty_coincide'] = false;
                            $fila['nivel_disty_registrado'] = '';
                            $fila['nivel_disty_coincide'] = false;
                            $fila['region_registrado'] = '';
                            $fila['region_coincide'] = false;
                        }
            
                        
            
                        return $fila;
                    });
            
            
                    return view('admin.usuario_importar_comparacion', [
                        'rows' => $rowData
                    ]);
                break;
        }

        
    
    }

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
                $usuario->password = Hash::make($registro['CONTRASEÑA']);
                $usuario->lista_correo = 'si';
                $usuario->imagen = 'default.jpg';
                $usuario->clase = 'usuario';
                $usuario->estado = 'activo';
    
                $usuario->save();
                echo'<td>Nuevo usuario</td><td>'.$usuario->nombre.' '.$usuario->apellidos.'</td><td>'.$usuario->email.'</td><td>'.$registro['CONTRASEÑA'].'</td>';
            }else{
                echo'<td>Usuario existente</td><td>'.$usuario->nombre.' '.$usuario->apellidos.'</td><td>'.$usuario->email.'</td><td>-</td>';
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
                $suscripcion->nivel_usuario = $registro['VENTAS / ESPECIALISTA'];
                
                if($registro['LÍDER']=='NO'){
                    $suscripcion->funcion = 'usuario';
                }else{
                    $suscripcion->funcion = 'lider';
                }
                
                //$suscripcion->funcion = 'usuario';
                
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

    /**
     * Funciones de excel
     */


}