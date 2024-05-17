<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use App\Mail\MiCorreoMailable;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function index() {
        //return "Controlador de inicio";
    
        return view('admin/home');

    }
    public function base_de_datos() {
        //return "Controlador de inicio";
    
        return view('admin/base_de_datos');

    }
    public function backup() {
       // Nombre del archivo de respaldo (puedes personalizarlo según tus necesidades)
        $backupFileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';

        // Ruta donde se almacenará el archivo de respaldo (puedes cambiarla según tus necesidades)
        $backupFilePath = storage_path('app/backups/' . $backupFileName);

        // Configura las credenciales de tu base de datos
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Construye el comando mysqldump
        $command = "mysqldump -h {$dbHost} -P {$dbPort} -u {$dbUser} -p{$dbPass} {$dbName} > {$backupFilePath}";

        // Ejecuta el comando utilizando Symfony Process
        $process = new Process(explode(' ', $command));
        $process->run();

        // Verifica si hubo algún error durante la ejecución
        if (!$process->isSuccessful()) {
            return response()->json(['error' => $process->getErrorOutput()]);
        }

        // El respaldo se realizó con éxito
        return response()->json(['success' => true, 'backup_file' => $backupFilePath]);

    }

    public function enviarCorreo(Request $request)
    {   

        switch ($request->correo) {
            case 'ganador_trivia':
                $data = [
                    'titulo' => '!Felicidades¡ Eres el ganador de la Trivia # ',
                    'contenido' => 'Fuiste el primero en contestar correctamente a las preguntas',
                    'boton_texto' => '',
                    'boton_enlace' => '#'
                ];
                break;
            case 'canje_recompenza':
                $data = [
                    'titulo' => '!Tu recompenza está en camino¡ ',
                    'contenido' => 'Gracias por elegir tu recompenza',
                    'boton_texto' => '',
                    'boton_enlace' => '#'
                ];
                break;
            case 'canje_recompenza':
                $data = [
                    'titulo' => '!Tu recompenza está en camino¡ ',
                    'contenido' => 'Gracias por elegir tu recompenza',
                    'boton_texto' => '',
                    'boton_enlace' => '#'
                ];
                break;
            case 'inscripcion_logro':
                $data = [
                    'titulo' => '!Bienvenido al desafío Champions¡ ',
                    'contenido' => 'Texto....',
                    'boton_texto' => '',
                    'boton_enlace' => '#'
                ];
                break;
            case 'nivel_completado':
                $data = [
                    'titulo' => '!Haz completado el nivel A de Champions¡ ',
                    'contenido' => 'Texto....',
                    'boton_texto' => '',
                    'boton_enlace' => '#'
                ];
                break;
            case 'pass_update':
                $data = [
                    'titulo' => '!Tu contraseña se ha actualizado¡ ',
                    'contenido' => 'Si no realizaste esta acción comunícate con...',
                    'boton_texto' => '',
                    'boton_enlace' => '#'
                ];
                break;
            case 'inscripcion_champions':
                $data = [
                    'titulo' => '!Bienvenido a champions¡ ',
                    'contenido' => 'Gracias por enviar tu información de Panduit University y haber visto todas las sesiones 2023',
                    'boton_texto' => '',
                    'boton_enlace' => '#'
                ];
                break;
            case 'registro_usuario':
                $data = [
                    'titulo' => '!Bienvenido a PL-Electrico¡',
                    'contenido' => 'Estos son tusdatos',
                    'boton_texto' => 'Entrar',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/'
                ];
                break;
            
            default:
                $data = [
                    'titulo' => '!Mensaje de prueba',
                    'contenido' => 'Este es el cuerpo de texto',
                    'boton_texto' => 'Visitar',
                    'boton_enlace' => 'https://pl-electrico.panduitlatam.com/'
                ];
                break;
        }
        

        Mail::to('marmocreativo@gmail.com')->send(new MiCorreoMailable($data));

        return 'Correo enviado con éxito';
    }
}
