<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

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
}
