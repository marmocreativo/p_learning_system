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
            $imagen->move(public_path('img/publicaciones'), $nombreImagen);
        }else{
            $nombreImagen = null;
        }

        $popup->id_cuenta = $request->IdCuenta;
        $popup->id_temporada = $request->IdTemporada;
        $popup->titulo = $request->Titulo;
        $popup->contenido = $request->Contenido;
        $popup->imagen = $nombreImagen;
        $popup->fecha_inicio = $request->FechaInicio;
        $popup->fecha_final = $request->FechaFinal;
        
        try {
            $popup->save();
            dd($popup);
            return redirect()->route('popups_lista', ['id_temporada' => $request->id_temporada])
                             ->with('success', 'Popup creado correctamente.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        

        //return redirect()->route('popups_lista', ['id_temporada'=>$request->IdTemporada]);
    }

}
