<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Popup;
use App\Models\Cintillo;
use App\Models\Temporada;
use App\Models\Cuenta;

class PopupsController extends Controller
{

    public function index(Request $request)
    {
        //
        $id_temporada = $request->input('id_temporada');
        $temporada = Temporada::find($id_temporada);
        $cuenta = Cuenta::find($temporada->id_cuenta);
        $popups = Popup::where(['id_temporada' => $id_temporada])->get();
        $cintillos = Cintillo::where(['id_temporada' => $id_temporada])->get();
        return view('admin/popups_lista', compact('cuenta', 'temporada','popups', 'cintillos'));
    }

    public function crear_popup(Request $request)
    {
        //
        $popup = new Popup();

        $request->validate([
            'Imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ajusta las reglas de validación según tus necesidades
        ]);
    
        if ($request->hasFile('Imagen')) {
            $imagen = $request->file('Imagen');
            $nombreImagen = 'popup_'.time().'.'.$imagen->extension();
            $imagen->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagen);
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
            $imagen->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagen);
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
            $imagen->move(base_path('../public_html/system.panduitlatam.com/img/publicaciones'), $nombreImagen);
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
