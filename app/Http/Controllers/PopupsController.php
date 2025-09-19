<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Popup;
use App\Models\PopupLider;
use App\Models\Cintillo;
use App\Models\Temporada;
use App\Models\Cuenta;
use Illuminate\Support\Facades\DB;

class PopupsController extends Controller
{

    public function index(Request $request)
{
    $id_temporada = $request->input('id_temporada');
    
    // Validar que existe la temporada
    $temporada = Temporada::find($id_temporada);
    if (!$temporada) {
        return redirect()->back()->with('error', 'Temporada no encontrada');
    }
    
    $cuenta = Cuenta::find($temporada->id_cuenta);
    if (!$cuenta) {
        return redirect()->back()->with('error', 'Cuenta no encontrada');
    }
    
    $cuentas = Cuenta::all();
    $color_barra_superior = $cuenta->fondo_menu;
    $logo_cuenta = 'https://system.panduitlatam.com/img/publicaciones/'.$cuenta->logotipo;
    
    // Consultas principales
    $popups = Popup::where('id_temporada', $id_temporada)->get();
    $popup_lideres = PopupLider::where('id_temporada', $id_temporada)->get();
    $cintillos = Cintillo::where('id_temporada', $id_temporada)->get();

    // Consulta de distribuidores con campos específicos para evitar conflictos
    $distribuidores = DB::table('distribuidores')
        ->join('distribuidores_suscripciones', 'distribuidores.id', '=', 'distribuidores_suscripciones.id_distribuidor')
        ->where('distribuidores_suscripciones.id_temporada', $id_temporada)
        ->select(
            'distribuidores.id as distribuidor_id',
            'distribuidores.nombre as distribuidor_nombre',
            // Agrega aquí los campos específicos que necesites
        )
        ->get();
    
    return view('admin/popups_lista', compact(
        'cuenta', 
        'cuentas', 
        'color_barra_superior', 
        'logo_cuenta', 
        'temporada',
        'popups', 
        'popup_lideres', 
        'cintillos', 
        'distribuidores'
    ));
}

    public function crear_popup(Request $request)
    {
        //
        $popup = new Popup();

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4048', // Ajusta las reglas de validación según tus necesidades
        ]);
    
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'popup_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = null;
        }

        $popup->id_cuenta = $request->IdCuenta;
        $popup->id_temporada = $request->IdTemporada;
        $popup->titulo = $request->Titulo;
        $popup->contenido = $request->Contenido;
        $popup->urls = $request->Urls;
        $popup->boton_texto = $request->BotonTexto;
        $popup->boton_link = $request->BotonLink;
        $popup->imagen = $nombreImagen;
        $popup->fecha_inicio = $request->FechaInicio;
        $popup->fecha_final = $request->FechaFinal;
        
        try {
            $popup->save();
            //dd($popup);
            return redirect()->route('popups', ['id_temporada' => $request->IdTemporada])
                             ->with('success', 'Popup creado correctamente.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
    }

    public function actualizar_popup(Request $request)
    {
        $popup = Popup::find($request->input('Identificador'));
        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);
    
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'popup_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = $popup->imagen;
        }

        
        $popup->id_cuenta = $request->IdCuenta;
        $popup->id_temporada = $request->IdTemporada;
        $popup->titulo = $request->Titulo;
        $popup->contenido = $request->Contenido;
        $popup->urls = $request->Urls;
        $popup->boton_texto = $request->BotonTexto;
        $popup->boton_link = $request->BotonLink;
        $popup->imagen = $nombreImagen;
        $popup->fecha_inicio = $request->FechaInicio;
        $popup->fecha_final = $request->FechaFinal;

        $popup->save();

        return redirect()->route('popups', ['id_temporada' => $request->IdTemporada])
                             ->with('success', 'Popup actualizado correctamente.');

    }

    public function crear_popup_lider(Request $request)
    {
        $popup_lider = new PopupLider();

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ArchivoDescarga' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt|max:10240', // 10MB máximo
        ]);

        // Procesar imagen
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'popup_lider_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        } else {
            $nombreImagen = null;
        }

        // Procesar archivo de descarga
        $enlaceBoton = $request->EnlaceBoton; // URL manual por defecto
        
        if ($request->hasFile('ArchivoDescarga')) {
            $archivo = $request->file('ArchivoDescarga');
            $nombreArchivo = 'descarga_popup_lider_'.time().'.'.$archivo->extension();
            $archivo->move(base_path('../public_html/archivos/descargas'), $nombreArchivo);
            
            // Generar la URL del archivo subido
            $enlaceBoton = 'https://system.panduitlatam.com/archivos/descargas/'.$nombreArchivo;
        }

        // Procesar distribuidores (recibir array directamente del select multiple)
        $distribuidores = $request->Distribuidores ?? [];

        $popup_lider->id_temporada = $request->IdTemporada;
        $popup_lider->titulo = $request->Titulo;
        $popup_lider->resumen = $request->Resumen;
        $popup_lider->imagen = $nombreImagen;
        $popup_lider->texto_boton = $request->TextoBoton;
        $popup_lider->enlace_boton = $enlaceBoton; // Ahora puede ser URL manual o archivo subido
        $popup_lider->distribuidores = $distribuidores;
        $popup_lider->estado = $request->Estado ?? 'borrador';
        
        try {
            $popup_lider->save();
            return redirect()->route('popups', ['id_temporada' => $request->IdTemporada])
                            ->with('success', 'Popup Lider creado correctamente.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function actualizar_popup_lider(Request $request)
    {
        $popup_lider = PopupLider::find($request->input('Identificador'));
        
        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ArchivoDescarga' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt|max:10240', // 10MB máximo
        ]);

        // Procesar imagen
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'popup_lider_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        } else {
            $nombreImagen = $popup_lider->imagen;
        }

        // Procesar archivo de descarga
        $enlaceBoton = $request->EnlaceBoton; // URL manual por defecto
        
        if ($request->hasFile('ArchivoDescarga')) {
            $archivo = $request->file('ArchivoDescarga');
            $nombreArchivo = 'descarga_popup_lider_'.time().'.'.$archivo->extension();
            $archivo->move(base_path('../public_html/archivos/descargas'), $nombreArchivo);
            
            // Generar la URL del archivo subido
            $enlaceBoton = 'https://system.panduitlatam.com/archivos/descargas/'.$nombreArchivo;
        }

        // Procesar distribuidores (recibir array directamente del select multiple)
        $distribuidores = $request->Distribuidores ?? [];

        $popup_lider->titulo = $request->Titulo;
        $popup_lider->resumen = $request->Resumen;
        $popup_lider->imagen = $nombreImagen;
        $popup_lider->texto_boton = $request->TextoBoton;
        $popup_lider->enlace_boton = $enlaceBoton; // Ahora puede ser URL manual o archivo subido
        $popup_lider->distribuidores = $distribuidores;
        $popup_lider->estado = $request->Estado ?? 'borrador';

        $popup_lider->save();

        return redirect()->route('popups', ['id_temporada' => $request->IdTemporada])
                        ->with('success', 'Popup Lider actualizado correctamente.');
    }

    public function borrar_popup_lider(string $id)
    {
        $popup_lider = PopupLider::find($id);
        $popup_lider->delete();
        
        return redirect()->back()
                         ->with('success', 'Popup Lider eliminado correctamente.');
    }

    public function crear_cintillo(Request $request)
    {
        //
        $cintillo = new Cintillo();

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);
    
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'cintillo_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = null;
        }

        $cintillo->id_cuenta = $request->IdCuenta;
        $cintillo->id_temporada = $request->IdTemporada;
        $cintillo->texto = $request->Texto;
        $cintillo->texto_boton = $request->TextoBoton;
        $cintillo->enlace_boton = $request->EnlaceBoton;
        $cintillo->imagen = $nombreImagen;
        $cintillo->fecha_inicio = $request->FechaInicio;
        $cintillo->fecha_final = $request->FechaFinal;
        //dd($cintillo);
        try {
            $cintillo->save();
            //dd($cintillo);
            return redirect()->route('popups', ['id_temporada' => $request->IdTemporada])
                             ->with('success', 'Popup creado correctamente.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
    }

    public function actualizar_cintillo(Request $request)
    {
        //
        $cintillo = Cintillo::find($request->input('Identificador'));


        $cintillo->id_cuenta = $request->IdCuenta;
        $cintillo->id_temporada = $request->IdTemporada;
        $cintillo->texto = $request->Texto;
        $cintillo->texto_boton = $request->TextoBoton;
        $cintillo->enlace_boton = $request->EnlaceBoton;
        $cintillo->imagen = '';
        $cintillo->fecha_inicio = $request->FechaInicio;
        $cintillo->fecha_final = $request->FechaFinal;
        //dd($cintillo);
        try {
            $cintillo->save();
            //dd($cintillo);
            return redirect()->route('popups', ['id_temporada' => $request->IdTemporada])
                             ->with('success', 'Cintillo actualizado correctamente.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
    }

     /**
     * Remove the specified resource from storage.
     */
    public function borrar_popup(string $id)
    {
        //
        $popup = Popup::find($id);
        $id_temporada = $popup->id_temporada;
        $popup->delete();
        return redirect()->route('popups', ['id_temporada' => $id_temporada])
                             ->with('success', 'Popup eliminado correctamente.');
    }

    public function borrar_cintillo(string $id)
    {
        //
        $cintillo = Cintillo::find($id);
        $id_temporada = $cintillo->id_temporada;
        $cintillo->delete();
        return redirect()->route('popups', ['id_temporada' => $id_temporada])
                             ->with('success', 'Cintillo eliminado correctamente.');
    }

}