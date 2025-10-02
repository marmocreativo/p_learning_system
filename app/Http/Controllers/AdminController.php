<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use App\Mail\MiCorreoMailable;
use Illuminate\Support\Facades\Mail;

// Agregar todos los imports de las clases de Mail
use App\Mail\ConfirmacionCanje;
use App\Mail\ConfirmacionCanjeUsuario;
use App\Mail\RestaurarPass;
use App\Mail\CambioPass;
use App\Mail\ConfirmacionNivelChampions;
use App\Mail\FinalizacionChampions;
use App\Mail\GanadorTrivia;
use App\Mail\InscripcionChampions;
use App\Mail\RegistroUsuario;
use App\Mail\DireccionTrivia;
use App\Mail\DesafioChampions;


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

    public function test_emails_form()
    {
        return view('admin/test_emails');
    }

    public function test_emails(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'tipo_email' => 'required|string'
        ]);

        $email = $request->input('email');
        $tipo = $request->input('tipo_email');

        try {
            $datos = $this->obtenerDatosPrueba($tipo);
            $mailClass = $this->obtenerClaseMail($tipo);
            
            if (!$mailClass) {
                return back()->with('error', 'Tipo de email no válido');
            }

            Mail::to($email)->send(new $mailClass($datos));
            
            return back()->with('success', "Email de prueba '{$tipo}' enviado correctamente a {$email}");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar email: ' . $e->getMessage());
        }
    }

    public function test_all_emails(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');
        $resultados = [];
        $tipos = $this->obtenerTiposEmail();

        foreach ($tipos as $tipo) {
            try {
                $datos = $this->obtenerDatosPrueba($tipo);
                $mailClass = $this->obtenerClaseMail($tipo);
                
                if ($mailClass) {
                    Mail::to($email)->send(new $mailClass($datos));
                    $resultados[$tipo] = 'Enviado correctamente';
                } else {
                    $resultados[$tipo] = 'Clase de mail no encontrada';
                }
                
                // Pequeña pausa entre emails para evitar problemas de rate limiting
                sleep(1);
                
            } catch (\Exception $e) {
                $resultados[$tipo] = 'Error: ' . $e->getMessage();
            }
        }

        return back()->with('resultados', $resultados);
    }

    private function obtenerTiposEmail()
    {
        return [
            'confirmacion_canje',
            'confirmacion_canje_usuario',
            'restaurar_pass',
            'cambio_pass',
            'confirmacion_nivel_champions',
            'finalizacion_champions',
            'ganador_trivia',
            'inscripcion_champions',
            'registro_usuario',
            'direccion_trivia',
            'desafio_champions'
        ];
    }

    private function obtenerClaseMail($tipo)
    {
        $clases = [
            'confirmacion_canje' => ConfirmacionCanje::class,
            'confirmacion_canje_usuario' => ConfirmacionCanjeUsuario::class,
            'restaurar_pass' => RestaurarPass::class,
            'cambio_pass' => CambioPass::class,
            'confirmacion_nivel_champions' => ConfirmacionNivelChampions::class,
            'finalizacion_champions' => FinalizacionChampions::class,
            'ganador_trivia' => GanadorTrivia::class,
            'inscripcion_champions' => InscripcionChampions::class,
            'registro_usuario' => RegistroUsuario::class,
            'direccion_trivia' => DireccionTrivia::class,
            'desafio_champions' => DesafioChampions::class,
        ];

        return $clases[$tipo] ?? null;
    }

    private function obtenerDatosPrueba($tipo)
    {
        $productosEjemplo = [
            (object)[
                'nombre' => 'Producto de Prueba 1',
                'variacion' => 'Color Azul',
                'cantidad' => 2,
                'creditos_totales' => 100
            ],
            (object)[
                'nombre' => 'Producto de Prueba 2', 
                'variacion' => 'Talla M',
                'cantidad' => 1,
                'creditos_totales' => 50
            ]
        ];

        switch ($tipo) {
            case 'confirmacion_canje':
                return [
                    'titulo' => 'PRUEBA - Un nuevo canje ha llegado',
                    'productos' => $productosEjemplo,
                    'boton_texto' => 'Ver detalles',
                    'boton_enlace' => 'https://example.com/admin'
                ];

            case 'confirmacion_canje_usuario':
                return [
                    'titulo' => 'PRUEBA - ¡El premio que seleccionaste ya está en camino!',
                    'productos' => $productosEjemplo
                ];

            case 'restaurar_pass':
                return [
                    'banner' => 'https://p-learning.panduitlatam.com/assets/images/micrositio/1600x-300-Email-Banner-PLe.jpg',
                    'boton_enlace' => 'https://example.com/restaurar/123/456'
                ];

            case 'cambio_pass':
                return [
                    'titulo' => 'PRUEBA - Tu contraseña de PLearning ha sido cambiada',
                    'contenido' => '<p>Esta es una prueba del email de cambio de contraseña.</p>',
                    'newpass' => 'nuevaPassword123',
                    'boton_enlace' => 'https://example.com/login'
                ];

            case 'confirmacion_nivel_champions':
                return [
                    'desafio' => 'Desafío de Prueba Champions',
                    'nivel' => 'A',
                    'estado' => 'completado',
                    'boton_enlace' => 'https://example.com/champions'
                ];

            case 'finalizacion_champions':
                return [
                    'titulo' => 'PRUEBA - ¡Desafío completado!',
                    'contenido' => '<p>Has superado todos los niveles del desafío de prueba.</p>',
                    'boton_texto' => 'Desafío Champions',
                    'boton_enlace' => 'https://example.com/champions'
                ];

            case 'ganador_trivia':
                return [
                    'titulo' => 'PRUEBA - Ganador trivia México',
                    'boton_texto' => 'Ver trivia',
                    'boton_enlace' => 'https://example.com/trivia',
                    'contenido' => '<p><b>¡Felicidades! Ganaste la trivia de prueba.</b></p>'
                ];

            case 'inscripcion_champions':
                return [
                    'titulo' => 'PRUEBA - ¡Has sido elegido para el Desafío Champions!',
                    'contenido' => '<p>Esta es una invitación de prueba al programa Champions.</p>',
                    'boton_texto' => 'Desafío Champions',
                    'boton_enlace' => 'https://example.com/champions'
                ];

            case 'registro_usuario':
                return [
                    'titulo' => 'PRUEBA - ¡Bienvenido a PLearning!',
                    'contenido' => '<p>Datos de acceso de prueba:</p>
                                   <table>
                                       <tr><th>Usuario:</th><td>usuario_prueba</td></tr>
                                       <tr><th>Contraseña:</th><td>password123</td></tr>
                                   </table>',
                    'boton_texto' => 'Entrar',
                    'boton_enlace' => 'https://example.com/login'
                ];

            case 'direccion_trivia':
                return [
                    'titulo' => 'PRUEBA - Dirección del ganador',
                    'contenido' => '<p>Información de dirección de prueba para envío de premio.</p>',
                    'boton_texto' => '',
                    'boton_enlace' => '#'
                ];

            case 'desafio_champions':
                return [
                    'titulo' => 'PRUEBA - Bienvenido Champion',
                    'contenido' => '<p>Mensaje de prueba para desafío champions.</p>',
                    'boton_texto' => 'Ir al desafío',
                    'boton_enlace' => 'https://example.com/champions'
                ];

            default:
                return [
                    'titulo' => 'Email de Prueba',
                    'contenido' => '<p>Este es un email de prueba.</p>',
                    'boton_texto' => 'Ver más',
                    'boton_enlace' => 'https://example.com'
                ];
        }
    }

}
