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
use App\Models\Sucursal;
use App\Models\DistribuidoresSuscripciones;
use App\Models\SesionVis;
use App\Models\PuntosExtra;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Imports\UsersImport;
use App\Imports\DistribuidoresImport;
use App\Imports\SucursalesImport;
use App\Imports\PuntosExtraImport;
use App\Imports\UsuariosImport;

class CsvController extends Controller
{

    public function puntos_extra_masivo(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx'
    ]);

    $import = new PuntosExtraImport;
    Excel::import($import, $request->file('file'));

    $rows = $import->rows;
    $resultados = [];
    $id_temporada = $request->input('id_temporada');
    $temporada = Temporada::find($id_temporada);
    $cuenta = Cuenta::find($temporada->id_cuenta);

    foreach ($rows as $row) {
        $correo = $row['correo'];
        $concepto = $row['concepto'];
        $puntos = $row['puntos'];

        $usuario = null;
        $suscripcion = null;
        $concepto_existe = null;
        $existe = '';
        $agregar = true;

        $usuario = User::where('email', $correo)->first();
        

        if(!empty($usuario)){
            // Reviso la suscripcion
            $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)->where('id_temporada', $id_temporada)->first();
        }else{
            $agregar = false;
            $existe .= 'El correo no es correcto';
        }

        if(!empty($suscripcion)){
            // reviso el concepto
            $concepto_existe = PuntosExtra::where('id_usuario', $usuario->id)->where('id_temporada', $id_temporada)->where('concepto', $concepto)->first();
        }else{
            $agregar = false;
            $existe .= 'El usuario no está suscrito a esta temporada';
        }

        if ($concepto_existe) {
            $agregar = false;
            $existe .= 'El concepto ya se había agregado';
        }

        if($agregar){
            $nuevo = new PuntosExtra;
            $nuevo->id_cuenta = $temporada->id_cuenta;
            $nuevo->id_temporada = $temporada->id;
            $nuevo->id_usuario = $usuario->id;  
            $nuevo->concepto = $concepto;  
            $nuevo->puntos = $puntos;
            $nuevo->fecha_registro = date('Y-m-d H:i:s');
            $nuevo->save();

            $existe .= 'Agregado correctamente';
            
        }

        $resultados[] = [
                'correo' => $correo,
                'concepto' => $concepto,
                'puntos' => $puntos,
                'existe' => $existe
            ];
    }

    return view('importacion.resultado_puntos', [
            'resultados' => $resultados,
            'id_temporada' => $id_temporada
        ]);
}

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
                    $user = $users->firstWhere('email', trim($row['correo']));
                    $fila['correo'] = $row['correo'];
                    if(!$user){
                        
                        try {
                            // Crear un nuevo usuario
                            $user = new User;
                
                            // Separar el nombre de usuario y dominio del correo
                            list($nombreUsuario, $dominio) = explode('@', trim($row['correo']));
                
                            // Generar un nombre de usuario único
                            $numerosAleatorios = rand(100, 999);
                            $nuevoNombreUsuario = $nombreUsuario . $numerosAleatorios;
                
                            // Asignar valores al usuario
                            $user->nombre = $row['nombre'];
                            $user->apellidos = $row['apellidos'];
                            $user->email = trim($row['correo']);
                            $user->legacy_id = $nuevoNombreUsuario;
                            $user->telefono = '';
                            $user->whatsapp = '';
                            $user->fecha_nacimiento = null;
                            $user->password = Hash::make($row['password']);
                            $user->lista_correo = 'si';
                            $user->imagen = 'default.jpg';
                            $user->clase = 'usuario';
                            $user->estado = 'activo';
                
                            // Guardar el usuario
                            $user->save();
                
                            // Marcar que el usuario fue registrado exitosamente
                            $fila['usuario_registrado'] = true;
                        } catch (\Illuminate\Database\QueryException $e) {
                            // Manejar la excepción si ocurre un error de duplicación u otro error de base de datos
                            if ($e->getCode() == 23000) {
                                // El código 23000 generalmente indica una violación de restricción de integridad, como un duplicado
                                // Puedes registrar el error o manejarlo de manera específica aquí
                                
                            } else {
                                // Re-lanzar la excepción si es otro tipo de error
                                throw $e;
                            }
                            $fila['usuario_registrado'] = false;
                        }
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
                    if (!$suscripcion&&$user->id) {
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
                
            case 'checar_suscripciones':
                    $row = array();
                    $suscripciones = UsuariosSuscripciones::where('id_temporada', $temporada->id)->get();
                    $total = 0;
                    $inexistentes = 0;
                    $suscritos = 0;
                    foreach($suscripciones as $suscripcion){
                        $total++;
                        $usuario = User::where('id', $suscripcion->id_usuario)->first();
                        
                        $row[$suscripcion->id]['id'] = $suscripcion->id;
                        $row[$suscripcion->id]['id_usuario'] = $suscripcion->id_usuario;
                        $row[$suscripcion->id]['id_temporada'] = $suscripcion->id_temporada;
                        if($usuario){
                            $row[$suscripcion->id]['correo'] = $usuario->email;

                            // Verificar si el correo del usuario está en la lista de correos del Excel
                            if ($emails->contains($usuario->email)) {
                                $row[$suscripcion->id]['suscrito'] = 'Sí';
                                $suscritos ++;
                            } else {
                                $row[$suscripcion->id]['suscrito'] = 'No';
                            }
                        }else{
                            $row[$suscripcion->id]['correo'] = '-';
                            $row[$suscripcion->id]['suscrito'] = '-';
                            $inexistentes++;
                        }
                        
                    }

                    //dd($row);
                    return view('admin.usuario_importar_verificar_inscripcion', [ 'rows' => $row, 'total' => $total, 'suscritos' => $suscritos, 'inexistentes' => $inexistentes ]);
            break;
            case 'borrar_suscripciones':
                $row = array();
                $suscripciones = UsuariosSuscripciones::where('id_temporada', $temporada->id)->get();
                $total = 0;
                $inexistentes = 0;
                $suscritos = 0;
                foreach($suscripciones as $suscripcion){
                    $total++;
                    $usuario = User::where('id', $suscripcion->id_usuario)->first();
                    
                    $row[$suscripcion->id]['id'] = $suscripcion->id;
                    $row[$suscripcion->id]['id_usuario'] = $suscripcion->id_usuario;
                    $row[$suscripcion->id]['id_temporada'] = $suscripcion->id_temporada;
                    if($usuario){
                        $row[$suscripcion->id]['correo'] = $usuario->email;

                        // Verificar si el correo del usuario está en la lista de correos del Excel
                        if ($emails->contains($usuario->email)) {
                            $row[$suscripcion->id]['suscrito'] = 'Sí';
                            $suscritos ++;
                        } else {
                            $row[$suscripcion->id]['suscrito'] = 'NO SUSCRITO';
                            UsuariosSuscripciones::where('id', $suscripcion->id)->delete();
                        }
                    }else{
                        UsuariosSuscripciones::where('id', $suscripcion->id)->delete();
                        $row[$suscripcion->id]['correo'] = 'NO HAY USUARIO';
                        $row[$suscripcion->id]['suscrito'] = 'NO SUSCRITO';
                        $inexistentes++;
                    }
                    
                }

                

        
                //dd($row);
                return view('admin.usuario_importar_verificar_inscripcion', [ 'rows' => $row, 'total' => $total, 'suscritos' => $suscritos, 'inexistentes' => $inexistentes ]);
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
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $import = new UsersImport;
        Excel::import($import, $request->file('file'));

        $rows = $import->rows;
        
        $emails = $rows->pluck('correo'); // Cambia 'correo' por el nombre real de la columna en tu Excel
        $temporada = Temporada::where('id', 6)->first();
        $cuenta = Cuenta::where('id', 1)->first();
        $users = User::whereIn('email', $emails)->get();
        
        $rowData = $rows->map(function($row) use ($users) {
            $user = $users->firstWhere('email', $row['correo']);
            
            //dd($distribuidor);
            if(!empty($user)){
                $visualizacion = SesionVis::where('id_usuario', $user->id)->where('id_sesion', $row['id_sesion'])->first();

                if(!empty($visualizacion)){
                    $fila = [
                        'correo' => $row['correo'], 
                        'correo_registrado' => $user->email, 
                        'fecha' => $row['fecha'],
                        'fecha_registrada' => $visualizacion->fecha_ultimo_video,
                        'id_visualizacion' => $visualizacion->id
                    ];
                    //$fecha = Carbon::createFromFormat('Y-m-d H:i:s', $row['fecha']);
                    //$visualizacion->fecha_ultimo_video = $fecha;
                    //$visualizacion->save();
                }else{
                    $fila = [
                        'correo' => $row['correo'], 
                        'correo_registrado' => $user->email, 
                        'fecha' => $row['fecha'],
                        'fecha_registrada' => '-',
                        'id_visualizacion' => '-'
                    ];
                }
                

            }else{
                $fila = [
                    'correo' => $row['correo'], 
                    'correo_registrado' => '-', 
                    'fecha' => $row['fecha'],
                    'fecha_registrada' => '-',
                    'id_visualizacion' => '-'
                ];
            }

            return $fila;
        });


        return view('admin.importar_sesiones_anteriores', [
            'rows' => $rowData
        ]);
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
     * Funciones 2025
     */

     public function imp_distribuidores_2025(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx',
        'id_temporada' => 'required|exists:temporadas,id'
    ]);

    $import = new DistribuidoresImport;
    Excel::import($import, $request->file('file'));
    $rows = $import->rows;

    $resultados = [];
    $id_temporada = $request->input('id_temporada');
    $temporada = Temporada::find($id_temporada);
    $cuenta = Cuenta::find($temporada->id_cuenta);

    foreach ($rows as $row) {
        $nombre = $row['nombre'];
        $pais = $row['pais'];
        $region = $row['region'];
        $default_pass = $row['default_pass'];
        $nivel = $row['nivel'];
        $accion = $row['acción'] ?? null;

        $registro = Distribuidor::where('nombre', $nombre)->first();
        $ya_existe = $registro ? 'Sí' : 'No';
        $ya_suscrito = 'No suscrito';

        // Si el distribuidor ya existe, actualiza sus datos (menos el nombre)
        if ($registro) {
            $registro->pais = $pais;
            $registro->region = $region;
            $registro->default_pass = $default_pass;
            $registro->nivel = $nivel;
            $registro->save();

            // Buscar suscripción
            $suscripcion = DistribuidoresSuscripciones::where('id_distribuidor', $registro->id)
                                ->where('id_temporada', $id_temporada)
                                ->first();

            if ($suscripcion) {
                $ya_suscrito = 'Suscrito';
            } else {
                $nueva_suscripcion = new DistribuidoresSuscripciones;

                $nueva_suscripcion->id_distribuidor = $registro->id;
                $nueva_suscripcion->id_cuenta = $cuenta->id;
                $nueva_suscripcion->id_temporada = $id_temporada;
                $nueva_suscripcion->cantidad_usuarios = '0';
                $nueva_suscripcion->nivel = 'completo';
                $nueva_suscripcion->save();
                $ya_suscrito = 'Suscripción creada';
            }
        } else {
            // Si no existe el distribuidor, puedes decidir si lo creas o lo ignoras
            // En este ejemplo solo lo reportamos en resultados
            $distribuidor = new Distribuidor;
            $distribuidor->nombre =  $nombre;
            $distribuidor->pais =  $pais;
            $distribuidor->region =  $region;
            $distribuidor->default_pass =  $default_pass;
            $distribuidor->nivel =  $nivel;
            $distribuidor->save();
            $ya_existe = 'Distribuidor creado';
        }

        $resultados[] = [
            'nombre' => $nombre,
            'pais_excel' => $pais,
            'region_excel' => $region,
            'default_pass_excel' => $default_pass,
            'nivel_excel' => $nivel,
            'accion' => $accion,
            'ya_existe' => $ya_existe,
            'suscripcion' => $ya_suscrito,
        ];
    }

    return view('importacion.resultado_distribuidores', [
        'resultados' => $resultados,
        'id_temporada' => $id_temporada
    ]);
}

public function importarSucursales(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx'
    ]);

    $import = new SucursalesImport;
    Excel::import($import, $request->file('file'));

    $rows = $import->rows;
    $resultados = [];
    $id_temporada = $request->input('id_temporada');
    $temporada = Temporada::find($id_temporada);
    $cuenta = Cuenta::find($temporada->id_cuenta);

    foreach ($rows as $row) {
        $nombreDistribuidor = $row['distribuidor'];
        $nombreSucursal = $row['nombre'];
        $accion = $row['acción'] ?? null;

        $distribuidor = Distribuidor::where('nombre', $nombreDistribuidor)->first();

        if ($distribuidor) {
            $sucursalExiste = Sucursal::where('id_distribuidor', $distribuidor->id)
                                      ->where('nombre', $nombreSucursal)
                                      ->exists();

            if(!$sucursalExiste){
                $nueva_sucursal = new Sucursal;
                $nueva_sucursal->id_distribuidor = $distribuidor->id;
                $nueva_sucursal->nombre = $nombreSucursal;
                $nueva_sucursal->save();
            }

            $existe = $sucursalExiste ? 'Sí' : 'No';
        } else {
            $existe = 'Distribuidor no encontrado';
        }

        $resultados[] = [
            'distribuidor' => $nombreDistribuidor,
            'sucursal' => $nombreSucursal,
            'accion' => $accion,
            'existe' => $existe
        ];
    }

    return view('importacion.resultado_sucursales', [
        'resultados' => $resultados,
        'id_temporada' => $id_temporada
    ]);
}

public function importarUsuarios(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx'
    ]);

    $import = new UsuariosImport;
    Excel::import($import, $request->file('file'));

    $rows = $import->rows;
    $resultados = [];
    $id_temporada = $request->input('id_temporada');
    $temporada = Temporada::find($id_temporada);
    $cuenta = Cuenta::find($temporada->id_cuenta);
    

    foreach ($rows as $row) {
        $emailUsuario = $row['email'];
        $nombreUsuario = $row['nombre'];
        $apellidosUsuario = $row['apellidos'];
        $usuarioUsuario = $row['usuario'];
        $distribuidorUsuario = $row['distribuidor'];
        $nombreSucursal = $row['sucursal'];
        $categoriaUsuario = $row['categoria'];
        $rolUsuario = $row['rol'];

        if (empty($usuarioUsuario)) {
            // Sacar la parte del email antes del @
            $baseUsuario = Str::before($emailUsuario, '@');
            $usuarioUsuario =  $this->generarLegacyIdUnico($baseUsuario);
        } else {
            $usuarioUsuario =  $this->generarLegacyIdUnico($usuarioUsuario);
        }
        
        $usuario = User::where('email', $emailUsuario)->first();
        $distribuidor = Distribuidor::where('nombre', $distribuidorUsuario)->first();
        $sucursal = Sucursal::where('id_distribuidor', $distribuidor->id)
                                      ->where('nombre', $nombreSucursal)
                                      ->first();

        $existe = '';
        $estado_suscripcion = '';
        $id_suscripcion = '';

        if(!$usuario){
            $usuario = new User;
            $usuario->nombre = $nombreUsuario;
            $usuario->apellidos = $apellidosUsuario;
            $usuario->email = $emailUsuario;
            $usuario->legacy_id = $usuarioUsuario;
            $usuario->password = Hash::make($distribuidor->default_pass);
            $usuario->lista_correo = 'no';
            $usuario->imagen = 'default.jpg';
            $usuario->clase = 'usuario';
            $usuario->estado = 'activo';
            $usuario->save();
            $existe = 'Nuevo usuario';

        }else{
            $usuario->nombre = $nombreUsuario;
            $usuario->apellidos = $apellidosUsuario;
            $usuario->save();
            $existe = 'Existente';
        }

        //Busco la suscripcion

        $suscripcion = UsuariosSuscripciones::where('id_usuario', $usuario->id)
                                            ->where('id_temporada', $id_temporada)
                                            ->first();

        if(!$suscripcion){
            $suscripcion = new UsuariosSuscripciones;
            $suscripcion->id_usuario = $usuario->id;
            $suscripcion->id_cuenta = $cuenta->id;
            $suscripcion->id_temporada = $id_temporada;
            $suscripcion->id_distribuidor = $distribuidor->id;
            $suscripcion->puntos_sesiones = 0;
            $suscripcion->puntos_evaluaciones = 0;
            $suscripcion->puntos_trivias = 0;
            $suscripcion->puntos_jackpot = 0;
            $suscripcion->puntos_extra = 0;
            $suscripcion->puntos_totales = 0;
            $suscripcion->champions_a = 'si';
            $suscripcion->champions_b = 'si';
            $suscripcion->region = $distribuidor->region;
            $suscripcion->pais = $distribuidor->pais;
            $suscripcion->confirmacion_puntos = 'pendiente';
            $suscripcion->nivel = $distribuidor->nivel;
            $suscripcion->nivel_usuario = $categoriaUsuario;
            $suscripcion->funcion = $rolUsuario;
            $suscripcion->funcion_region = $distribuidor->region;
            $suscripcion->temporada_completa = 'no';
            $suscripcion->id_sucursal = $sucursal ? $sucursal->id : null;
            $suscripcion->fecha_terminos = null;
            $suscripcion->save();
            $estado_suscripcion = 'Nueva suscripcion';
        }else{
            $suscripcion->id_distribuidor = $distribuidor->id;
            $suscripcion->region = $distribuidor->region;
            $suscripcion->pais = $distribuidor->pais;
            $suscripcion->nivel = $distribuidor->nivel;
            $suscripcion->nivel_usuario = $categoriaUsuario;
            $suscripcion->funcion = $rolUsuario;
            $suscripcion->funcion_region = $distribuidor->region;
            $suscripcion->id_sucursal = $sucursal ? $sucursal->id : null;
            $suscripcion->save();
            $estado_suscripcion = 'Suscripcion actualizada';
        }
        $id_suscripcion = $suscripcion->id;

        $resultados[] = [
            'nombre' => $nombreUsuario,
            'apellidos' => $apellidosUsuario,
            'email' => $emailUsuario,
            'existe' => $existe,
            'suscripcion' => $estado_suscripcion,
            'id_suscripcion' => $id_suscripcion
        ];
    }

    return view('importacion.resultado_usuarios', [
        'resultados' => $resultados,
        'id_temporada' => $id_temporada
    ]);
}

private function generarLegacyIdUnico($base)
{
    $id = $base;
    $contador = 1;

    while (User::where('legacy_id', $id)->exists()) {
        $id = $base . $contador;
        $contador++;
    }

    return $id;
}

}