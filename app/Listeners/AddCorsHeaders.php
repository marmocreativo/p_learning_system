<?php

namespace App\Listeners;

use App\Events\ModifyResponse;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddCorsHeaders implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param ModifyResponse $event
     * @return void
     */
    public function handle(ModifyResponse $event)
    {
        $response = $event->response;

        // Agregar los encabezados CORS
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
