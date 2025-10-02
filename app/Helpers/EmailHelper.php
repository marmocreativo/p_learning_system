<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailHelper
{
    public static function enviarEmail($tipo, $destinatario, $datos = [])
    {
        try {
            $mailClass = self::obtenerClaseMail($tipo);
            
            if (!$mailClass) {
                throw new \Exception("Tipo de email no válido: {$tipo}");
            }
            
            Mail::to($destinatario)->send(new $mailClass($datos));
            
            return ['success' => true, 'message' => 'Email enviado correctamente'];
        } catch (\Exception $e) {
            Log::error("Error al enviar email tipo {$tipo}: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private static function obtenerClaseMail($tipo)
    {
        $clases = [
            'confirmacion_canje' => \App\Mail\ConfirmacionCanje::class,
            'confirmacion_canje_usuario' => \App\Mail\ConfirmacionCanjeUsuario::class,
            'restaurar_pass' => \App\Mail\RestaurarPass::class,
            'cambio_pass' => \App\Mail\CambioPass::class,
            'confirmacion_nivel_champions' => \App\Mail\ConfirmacionNivelChampions::class,
            'finalizacion_champions' => \App\Mail\FinalizacionChampions::class,
            'ganador_trivia' => \App\Mail\GanadorTrivia::class,
            'inscripcion_champions' => \App\Mail\InscripcionChampions::class,
            'registro_usuario' => \App\Mail\RegistroUsuario::class,
        ];

        return $clases[$tipo] ?? null;
    }
}